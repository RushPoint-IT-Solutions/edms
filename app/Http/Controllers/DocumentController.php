<?php

namespace App\Http\Controllers;

use App\ApproverStamp;
use App\ChangeRequest;
use App\Document;
use App\Obsolete;
use App\Department;
use App\DocumentType;
use App\DocumentAttachment;
use App\Company;
// use App\DateApprovedLog;
use App\DocumentFolder;
use App\DocumentRequestAccess;
use App\DocumentSignaturePosition;
use App\DocumentTag;
use App\DocumentTypeList;
use App\DocumentVisitor;
use App\History;
use App\Mail\ApprovedDateEmail;
use App\RequestApprover;
use App\ShareDocument;
use App\User;
use App\Team;
use App\ControlCode;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use \setasign\Fpdi\PdfParser\StreamReader;
use \setasign\Fpdi\PdfParser\CrossReference;
use Illuminate\Support\Facades\Redirect;

use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\Filesystem\Filesystem;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!canView('personal.view')) {
            return view('pages.403-error');
        }
    
        $document_folders = DocumentFolder::with('document', 'childrenFolder')
            ->where('user_id', auth()->user()->id)
            ->get();

        $users = User::whereNull('status')->get();
    
        $documents = Document::with('change_requests', 'attachments', 'share_document')
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        $document_folders = $document_folders->map(function ($folder) {
            if ($folder->document) {
                $folder->document = $folder->document->map(function ($doc) {
                    $fileInfo = $this->getDocumentFileInfo($doc);
                    $doc->fileType    = $fileInfo['fileType'];
                    $doc->previewClass = $fileInfo['previewClass'];
                    $doc->iconClass   = $fileInfo['iconClass'];
                    $doc->badgeClass  = $fileInfo['badgeClass'];
                    return $doc;
                });
            }
            return $folder;
        });
    
        $documents = $documents->map(function ($doc) {
            $fileInfo = $this->getDocumentFileInfo($doc);
            $doc->fileType    = $fileInfo['fileType'];
            $doc->previewClass = $fileInfo['previewClass'];
            $doc->iconClass   = $fileInfo['iconClass'];
            $doc->badgeClass  = $fileInfo['badgeClass'];
            return $doc;
        });
    
        $allFolders  = $document_folders->where('parent_id', null);
        $hasOthers   = $documents->where('folder_id', null)->count() > 0;
        $totalFolders = $allFolders->count() + ($hasOthers ? 1 : 0);
    
        $folderData = $document_folders->map(function ($f) {
            return [
                'id' => $f->id,
                'name' => $f->name,
                'docs' => $f->document->map(function ($d) {
                    $fileInfo = $this->getDocumentFileInfo($d);
                    $originalFileName = $fileInfo['originalFileName'];

                    $label = $d->title;
                    if ($originalFileName) {
                        $label .= ' - ' . $originalFileName;
                    }

                    return [
                        'id'    => $d->id,
                        'label' => $label,
                    ];
                })->values(),
            ];
        })->values();
    
        $shareTree = $this->buildShareTree($document_folders, $documents);
        $shareOthersDocs = $documents->filter(fn($d) => is_null($d->folder_id))->map(function($d) {
            $fileInfo = $this->getDocumentFileInfo($d);
            $originalFileName = $fileInfo['originalFileName'];

            $label = $d->title;
            if ($originalFileName) {
                $label .= ' - ' . $originalFileName;
            }

            return [
                'id' => $d->id,
                'label' => $label,
            ];
        })->values()->toArray();
    
        $upload_folders = DocumentFolder::with('document', 'childrenFolder')
            ->where('user_id', auth()->user()->id)
            ->get();
    
        return view('documents.documents', [
            'documents'        => $documents,
            'document_folders' => $document_folders,
            'totalFolders'     => $totalFolders,
            'allFolders'       => $allFolders,
            'hasOthers'        => $hasOthers,
            'users'            => $users,
            'folderData'       => $folderData,
            'shareTree'        => $shareTree,
            'shareOthersDocs'  => $shareOthersDocs,
            'upload_folders'   => $upload_folders,
        ]);
    }

    public function getFolderTree(Request $request)
    {
        $document_folders = DocumentFolder::with('document', 'childrenFolder')
                    ->where('user_id', auth()->user()->id)
                    ->get();

        $documents = Document::with('change_requests', 'attachments')
            ->where('user_id', auth()->user()->id)
            ->orderBy('control_code', 'desc')
            ->get();

        $documents = $documents->map(function ($doc) {
            $fileInfo = $this->getDocumentFileInfo($doc);
            $doc->fileType = $fileInfo['fileType'];
            $doc->previewClass = $fileInfo['previewClass'];
            $doc->iconClass = $fileInfo['iconClass'];
            $doc->badgeClass = $fileInfo['badgeClass'];
            $doc->originalFileName = $fileInfo['originalFileName'];
            return $doc;
        });

        $allFolders = $document_folders->where('parent_id', null);
        $hasOthers = $documents->where('folder_id', null)->count() > 0;
        $totalFolders = $allFolders->count() + ($hasOthers ? 1 : 0);

        $listHtml = $this->renderFolderTreeView($document_folders, $documents, null, 0, null);

        if ($hasOthers) {
            $othersDocuments = $documents->where('folder_id', null);
            $hasOthersChildren = count($othersDocuments) > 0;

            $canDelete = canDelete('documents');

            $listHtml .= '<tr class="folder-tree-row ' . ($hasOthersChildren ? 'has-children' : '') . '"
                data-folder-name="others"
                data-folder-id="others"
                data-level="0">
                <td class="checkbox-cell" onclick="event.stopPropagation()">
                    <input type="checkbox" class="item-checkbox form-check-input"
                        data-type="others" data-id="others" data-name="Others"
                        disabled title="System folder — cannot be deleted"
                        style="opacity: 0.35; cursor: not-allowed;">
                </td>
                <td class="folder-name-cell"
                    data-folder-url="' . url('documents/folder/others') . '"
                    onclick="handleFolderClick(this, ' . ($hasOthersChildren ? 'true' : 'false') . ')">
                    <div class="name-cell">
                        ' . ($hasOthersChildren
                            ? '<span class="folder-toggle"><i class="ri-arrow-right-s-line"></i></span>'
                            : '<span style="width:20px;display:inline-block;"></span>') . '
                        <i class="ri-folder-2-fill item-icon" style="color:#9ca3af;"></i>
                        <span class="item-name" style="color:#9ca3af;font-style:italic;">Others</span>
                    </div>
                </td>
                <td>Folder</td>
                <td>—</td>
                <td>—</td>
                <td class="actions-cell" onclick="event.stopPropagation()">
                    <button class="action-btn"><i class="ri-more-2-fill"></i></button>
                </td>
            </tr>';

            foreach ($othersDocuments as $doc) {
                $fileInfo    = $this->getDocumentFileInfo($doc);
                $cleanFileName = $fileInfo['originalFileName']
                    ? $doc->title . ' - ' . $fileInfo['originalFileName']
                    : $doc->title;

                $escapedDisplay = htmlspecialchars($cleanFileName, ENT_QUOTES);
                $escapedForJs   = addslashes($escapedDisplay);

                $listHtml .= '<tr class="child-row"
                    data-parent-id="others"
                    data-level="1"
                    data-document-id="' . $doc->id . '"
                    onclick="window.open(\'' . url('documents/view-document/' . $doc->id) . '\', \'_blank\')">
                    <td class="checkbox-cell" onclick="event.stopPropagation()">
                        <input type="checkbox" class="item-checkbox form-check-input"
                            data-type="document"
                            data-id="' . $doc->id . '"
                            data-name="' . $escapedDisplay . '"
                            onchange="handleFolderCheckbox(this)">
                    </td>
                    <td>
                        <div class="name-cell">
                            <span class="folder-indent" style="width:24px;"></span>
                            <span style="width:20px;display:inline-block;"></span>
                            <i class="ri-file-text-line item-icon" style="color:#6b7280;"></i>
                            <span class="item-name">' . $escapedDisplay . '</span>
                        </div>
                    </td>
                    <td>' . strtoupper($fileInfo['fileType']) . '</td>
                    <td>—</td>
                    <td>' . date('M d, Y', strtotime($doc->updated_at)) . '</td>
                    <td class="actions-cell" onclick="event.stopPropagation()">';

                if ($canDelete) {
                    $listHtml .= '<div class="dropdown">
                        <button class="action-btn" data-bs-toggle="dropdown" onclick="event.stopPropagation()">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item text-danger" href="javascript:void(0)"
                                    onclick="event.stopPropagation(); deleteDocument(' . $doc->id . ', \'' . $escapedForJs . '\')">
                                    <i class="ri-delete-bin-line me-2"></i>Delete document
                                </a>
                            </li>
                        </ul>
                    </div>';
                } else {
                    $listHtml .= '<button class="action-btn"><i class="ri-more-2-fill"></i></button>';
                }

                $listHtml .= '</td></tr>';
            }
        }

        $canEdit = canEdit('personal.edit_folder');
        $canDelete = canDelete('personal.delete_folder');

        $gridHtml = '';
        foreach ($allFolders as $folder) {
            $dropdownItems = '';
            if ($canEdit) {
                $dropdownItems .= '<li>
                    <a class="dropdown-item" href="javascript:void(0)"
                        data-bs-toggle="modal"
                        data-bs-target="#renameFolderModal' . $folder->id . '"
                        onclick="event.stopPropagation()">
                        <i class="ri-pencil-line me-2"></i>Rename folder
                    </a>
                </li>';
            }
            if ($canDelete) {
                $dropdownItems .= '<li>
                    <a class="dropdown-item text-danger delete-folder-btn" href="javascript:void(0)"
                        data-id="' . $folder->id . '"
                        data-name="' . htmlspecialchars($folder->name, ENT_QUOTES) . '">
                        <i class="ri-delete-bin-line me-2"></i>Delete folder
                    </a>
                </li>';
            }

            $gridHtml .= '<div class="grid-item"
                data-folder-id="' . $folder->id . '"
                data-folder-name="' . strtolower(htmlspecialchars($folder->name, ENT_QUOTES)) . '"
                data-type="folder"
                data-id="' . $folder->id . '"
                onclick="handleGridItemClick(event, this, \'' . url('documents/folder/' . $folder->id) . '\')">
                <div class="grid-item-header">
                    <input type="checkbox"
                        class="grid-item-checkbox item-checkbox form-check-input"
                        data-type="folder"
                        data-id="' . $folder->id . '"
                        data-name="' . htmlspecialchars($folder->name, ENT_QUOTES) . '"
                        onclick="event.stopPropagation(); handleGridCheckbox(this)">
                    <button class="grid-item-menu" onclick="event.stopPropagation()"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">' . $dropdownItems . '</ul>
                </div>
                <div class="grid-item-icon">
                    <i class="ri-folder-2-fill"></i>
                </div>
                <div class="grid-item-name">' . htmlspecialchars($folder->name) . '</div>
                <div class="grid-item-meta">' . date('M d, Y', strtotime($folder->updated_at)) . '</div>
            </div>';
        }

        if ($hasOthers) {
            $gridHtml .= '<div class="grid-item"
                data-folder-name="others"
                data-type="others"
                data-id="others"
                onclick="handleGridItemClick(event, this, \'' . url('documents/folder/others') . '\')">
                <div class="grid-item-header">
                    <input type="checkbox"
                        class="grid-item-checkbox item-checkbox form-check-input"
                        data-type="others"
                        data-id="others"
                        data-name="Others"
                        disabled
                        title="System folder — cannot be deleted"
                        style="opacity:0.35;cursor:not-allowed;"
                        onclick="event.stopPropagation()">
                    <button class="grid-item-menu" onclick="event.stopPropagation()">
                        <i class="ri-more-2-fill"></i>
                    </button>
                </div>
                <div class="grid-item-icon">
                    <i class="ri-folder-2-fill" style="color:#9ca3af;"></i>
                </div>
                <div class="grid-item-name" style="color:#9ca3af;font-style:italic;">Others</div>
                <div class="grid-item-meta">—</div>
            </div>';
        }

        return response()->json([
            'listHtml' => $listHtml,
            'gridHtml' => $gridHtml,
            'totalFolders' => $totalFolders,
        ]);
    }

    public function getFolderViewTree(Request $request, $id)
    {
        $canEdit = canEdit('documents');
        $canDelete = canDelete('documents');
        $search = $request->input('search');

        if ($id === 'others') {
            $documentsQuery = Document::with('change_requests', 'attachments')
                ->whereNull('folder_id')
                ->where('user_id', auth()->user()->id);

            if ($search) {
                $documentsQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('control_code', 'like', '%' . $search . '%');
                });
            }

            $documents = $documentsQuery->orderBy('control_code', 'desc')->get()->map(function ($doc) {
                $info = $this->getDocumentFileInfo($doc);
                $doc->fileType = $info['fileType'];
                $doc->previewClass = $info['previewClass'];
                $doc->iconClass = $info['iconClass'];
                $doc->badgeClass = $info['badgeClass'];
                $doc->originalFileName = $info['originalFileName'];
                return $doc;
            });

            $listHtml = '';
            $gridHtml = '';

            foreach ($documents as $doc) {
                $cleanFileName  = $doc->originalFileName
                    ? $doc->title . ' - ' . $doc->originalFileName
                    : $doc->title;
                $escapedDisplay = htmlspecialchars($cleanFileName, ENT_QUOTES);
                $escapedForJs = addslashes($escapedDisplay);
                $docUrl = url('/documents/view-document/' . $doc->id);

                $shareListItem = '<li>
                    <a class="dropdown-item" href="javascript:void(0)"
                        onclick="event.stopPropagation(); preSingleDocShare(' . $doc->id . ', \'' . $escapedForJs . '\')">
                        <i class="ri-share-line me-2"></i>Share
                    </a>
                </li>';

                $actionHtml = '<div class="dropdown">
                    <button class="action-btn" data-bs-toggle="dropdown" onclick="event.stopPropagation()">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        ' . $shareListItem .
                        ($canDelete ? '<li>
                            <a class="dropdown-item text-danger" href="javascript:void(0)"
                                onclick="event.stopPropagation(); deleteDocument(' . $doc->id . ', \'' . $escapedForJs . '\')">
                                <i class="ri-delete-bin-line me-2"></i>Delete document
                            </a>
                        </li>' : '') . '
                    </ul>
                </div>';

                $listHtml .= '
                <tr class="document-row"
                    data-type="' . $doc->fileType . '"
                    data-modified="' . $doc->updated_at . '"
                    data-document-id="' . $doc->id . '"
                    onclick="window.open(\'' . $docUrl . '\', \'_blank\')">
                    <td class="checkbox-cell" onclick="event.stopPropagation()">
                        <input type="checkbox" class="item-checkbox form-check-input"
                            data-type="document"
                            data-id="' . $doc->id . '"
                            data-name="' . $escapedDisplay . '">
                    </td>
                    <td>
                        <div class="name-cell">
                            <i class="' . $doc->iconClass . ' item-icon" style="color:#6b7280;"></i>
                            <span class="item-name">' . $escapedDisplay . '</span>
                        </div>
                    </td>
                    <td>' . strtoupper($doc->fileType) . '</td>
                    <td>—</td>
                    <td>' . date('M d, Y', strtotime($doc->updated_at)) . '</td>
                    <td class="actions-cell" onclick="event.stopPropagation()">' . $actionHtml . '</td>
                </tr>';

                $gridDropdown = '<ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0)"
                            onclick="event.stopPropagation(); preSingleDocShare(' . $doc->id . ', \'' . $escapedForJs . '\')">
                            <i class="ri-share-line me-2"></i>Share
                        </a>
                    </li>' .
                    ($canDelete ? '<li>
                        <a class="dropdown-item text-danger" href="javascript:void(0)"
                            onclick="event.stopPropagation(); deleteDocument(' . $doc->id . ', \'' . $escapedForJs . '\')">
                            <i class="ri-delete-bin-line me-2"></i>Delete document
                        </a>
                    </li>' : '') . '
                </ul>';

                $gridHtml .= '
                <div class="grid-item file-item"
                    data-type="' . $doc->fileType . '"
                    data-modified="' . $doc->updated_at . '"
                    data-document-id="' . $doc->id . '"
                    onclick="window.open(\'' . $docUrl . '\', \'_blank\')">
                    <div class="grid-item-header">
                        <input type="checkbox" class="form-check-input grid-item-checkbox item-checkbox"
                            data-type="document"
                            data-id="' . $doc->id . '"
                            data-name="' . $escapedDisplay . '"
                            onclick="event.stopPropagation(); handleGridCheckbox(this)">
                        <button class="grid-item-menu" onclick="event.stopPropagation()"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        ' . $gridDropdown . '
                    </div>
                    <div class="grid-item-preview ' . $doc->previewClass . '">
                        <i class="' . $doc->iconClass . ' grid-item-icon"></i>
                    </div>
                    <div class="grid-item-info">
                        <div class="grid-item-name">' . $escapedDisplay . '</div>
                        <div class="grid-item-meta">
                            <span class="file-type-badge ' . $doc->badgeClass . '">' . strtoupper($doc->fileType) . '</span>
                            <span>' . date('M d', strtotime($doc->updated_at)) . '</span>
                        </div>
                    </div>
                </div>';
            }

            return response()->json([
                'listHtml' => $listHtml,
                'gridHtml' => $gridHtml,
                'totalFolders' => 0,
                'totalDocuments' => $documents->count(),
                'totalItems' => $documents->count(),
            ]);
        }

        $all_document_folders = DocumentFolder::where('user_id', auth()->user()->id)->get();

        $foldersQuery = DocumentFolder::where('parent_id', $id);
        $documentsQuery = Document::where('folder_id', $id);

        if ($search) {
            $foldersQuery->where('name', 'like', '%' . $search . '%');
            $documentsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('control_code', 'like', '%' . $search . '%');
            });
        }

        $childFolders = $foldersQuery->orderBy('name', 'asc')->get();

        $childDocuments = $documentsQuery->orderBy('control_code', 'desc')->get()->map(function ($doc) {
            $info = $this->getDocumentFileInfo($doc);
            $doc->fileType = $info['fileType'];
            $doc->previewClass = $info['previewClass'];
            $doc->iconClass = $info['iconClass'];
            $doc->badgeClass = $info['badgeClass'];
            $doc->originalFileName = $info['originalFileName'];
            return $doc;
        });

        $allDocuments = Document::all();
        $listHtml = $this->renderFolderTreeView($all_document_folders, $allDocuments, $id, 0, $id);

        foreach ($childDocuments as $doc) {
            $cleanFileName  = $doc->originalFileName
                ? $doc->title . ' - ' . $doc->originalFileName
                : $doc->title;
            $escapedDisplay = htmlspecialchars($cleanFileName, ENT_QUOTES);
            $escapedForJs = addslashes($escapedDisplay);
            $docUrl = url('/documents/view-document/' . $doc->id);

            $shareListItem = '<li>
                <a class="dropdown-item" href="javascript:void(0)"
                    onclick="event.stopPropagation(); preSingleDocShare(' . $doc->id . ', \'' . $escapedForJs . '\')">
                    <i class="ri-share-line me-2"></i>Share
                </a>
            </li>';

            $actionHtml = '<div class="dropdown">
                <button class="action-btn" data-bs-toggle="dropdown" onclick="event.stopPropagation()">
                    <i class="ri-more-2-fill"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    ' . $shareListItem .
                    ($canDelete ? '<li>
                        <a class="dropdown-item text-danger" href="javascript:void(0)"
                            onclick="event.stopPropagation(); deleteDocument(' . $doc->id . ', \'' . $escapedForJs . '\')">
                            <i class="ri-delete-bin-line me-2"></i>Delete document
                        </a>
                    </li>' : '') . '
                </ul>
            </div>';

            $listHtml .= '
            <tr class="document-row"
                data-type="' . $doc->fileType . '"
                data-modified="' . $doc->updated_at . '"
                data-document-id="' . $doc->id . '"
                onclick="window.open(\'' . $docUrl . '\', \'_blank\')">
                <td class="checkbox-cell" onclick="event.stopPropagation()">
                    <input type="checkbox" class="item-checkbox form-check-input"
                        data-type="document"
                        data-id="' . $doc->id . '"
                        data-name="' . $escapedDisplay . '">
                </td>
                <td>
                    <div class="name-cell">
                        <i class="' . $doc->iconClass . ' item-icon" style="color:#6b7280;"></i>
                        <span class="item-name">' . $escapedDisplay . '</span>
                    </div>
                </td>
                <td>' . strtoupper($doc->fileType) . '</td>
                <td>—</td>
                <td>' . date('M d, Y', strtotime($doc->updated_at)) . '</td>
                <td class="actions-cell" onclick="event.stopPropagation()">' . $actionHtml . '</td>
            </tr>';
        }

        $gridHtml = '';

        foreach ($childFolders as $folder) {
            $dropdownItems = '<li>
                <a class="dropdown-item" href="javascript:void(0)"
                    onclick="event.stopPropagation(); preSingleFolderShare(' . $folder->id . ')">
                    <i class="ri-share-line me-2"></i>Share folder
                </a>
            </li>';

            if ($canEdit) {
                $dropdownItems .= '<li>
                    <a class="dropdown-item" href="javascript:void(0)"
                        data-bs-toggle="modal"
                        data-bs-target="#renameFolderModal' . $folder->id . '"
                        onclick="event.stopPropagation()">
                        <i class="ri-pencil-line me-2"></i>Rename folder
                    </a>
                </li>';
            }
            if ($canDelete) {
                $dropdownItems .= '<li>
                    <a class="dropdown-item text-danger delete-folder-btn" href="javascript:void(0)"
                        data-id="' . $folder->id . '"
                        data-name="' . htmlspecialchars($folder->name, ENT_QUOTES) . '">
                        <i class="ri-delete-bin-line me-2"></i>Delete folder
                    </a>
                </li>';
            }

            $gridHtml .= '
            <div class="grid-item folder-item"
                data-folder-id="' . $folder->id . '"
                data-type="folder"
                data-modified="' . $folder->updated_at . '"
                onclick="window.location=\'' . url('documents/folder/' . $folder->id) . '\'">
                <div class="grid-item-header">
                    <input type="checkbox" class="form-check-input grid-item-checkbox item-checkbox"
                        data-type="folder"
                        data-id="' . $folder->id . '"
                        data-name="' . htmlspecialchars($folder->name, ENT_QUOTES) . '"
                        onclick="event.stopPropagation(); handleGridCheckbox(this)">
                    <button class="grid-item-menu" onclick="event.stopPropagation()"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">' . $dropdownItems . '</ul>
                </div>
                <div class="grid-item-icon">
                    <i class="ri-folder-2-fill"></i>
                </div>
                <div class="grid-item-name">' . htmlspecialchars($folder->name) . '</div>
                <div class="grid-item-meta">' . date('M d, Y', strtotime($folder->updated_at)) . '</div>
            </div>';
        }

        foreach ($childDocuments as $doc) {
            $cleanFileName  = $doc->originalFileName
                ? $doc->title . ' - ' . $doc->originalFileName
                : $doc->title;
            $escapedDisplay = htmlspecialchars($cleanFileName, ENT_QUOTES);
            $escapedForJs = addslashes($escapedDisplay);
            $docUrl = url('/documents/view-document/' . $doc->id);

            $gridDropdown = '<ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="javascript:void(0)"
                        onclick="event.stopPropagation(); preSingleDocShare(' . $doc->id . ', \'' . $escapedForJs . '\')">
                        <i class="ri-share-line me-2"></i>Share
                    </a>
                </li>' .
                ($canDelete ? '<li>
                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                        onclick="event.stopPropagation(); deleteDocument(' . $doc->id . ', \'' . $escapedForJs . '\')">
                        <i class="ri-delete-bin-line me-2"></i>Delete document
                    </a>
                </li>' : '') . '
            </ul>';

            $gridHtml .= '
            <div class="grid-item file-item"
                data-type="' . $doc->fileType . '"
                data-modified="' . $doc->updated_at . '"
                data-document-id="' . $doc->id . '"
                onclick="window.open(\'' . $docUrl . '\', \'_blank\')">
                <div class="grid-item-header">
                    <input type="checkbox" class="form-check-input grid-item-checkbox item-checkbox"
                        data-type="document"
                        data-id="' . $doc->id . '"
                        data-name="' . $escapedDisplay . '"
                        onclick="event.stopPropagation(); handleGridCheckbox(this)">
                    <button class="grid-item-menu" onclick="event.stopPropagation()"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    ' . $gridDropdown . '
                </div>
                <div class="grid-item-preview ' . $doc->previewClass . '">
                    <i class="' . $doc->iconClass . ' grid-item-icon"></i>
                </div>
                <div class="grid-item-info">
                    <div class="grid-item-name">' . $escapedDisplay . '</div>
                    <div class="grid-item-meta">
                        <span class="file-type-badge ' . $doc->badgeClass . '">' . strtoupper($doc->fileType) . '</span>
                        <span>' . date('M d', strtotime($doc->updated_at)) . '</span>
                    </div>
                </div>
            </div>';
        }

        return response()->json([
            'listHtml' => $listHtml,
            'gridHtml' => $gridHtml,
            'totalFolders' => $childFolders->count(),
            'totalDocuments' => $childDocuments->count(),
            'totalItems' => $childFolders->count() + $childDocuments->count(),
        ]);
    }

    private function renderFolderTreeView($allFolders, $documents, $currentFolderId, $level = 0, $parentId = null)
    {
        $html = '';
        $canEdit   = canEdit('documents');
        $canDelete = canDelete('documents');

        $filteredFolders = $allFolders->where('parent_id', $parentId);

        foreach ($filteredFolders as $folder) {
            $childFolders    = $allFolders->where('parent_id', $folder->id);
            $folderDocuments = $documents->where('folder_id', $folder->id);
            $hasChildren     = count($folderDocuments) > 0 || count($childFolders) > 0;

            $rowClass = 'folder-tree-row document-row ' . ($hasChildren ? 'has-children' : '');
            if ($level > 0) {
                $rowClass = 'child-row folder-tree-row document-row ' . ($hasChildren ? 'has-children' : '');
            }

            $html .= '<tr class="' . $rowClass . '" ';
            if ($level > 0) {
                $html .= 'data-parent-id="' . $parentId . '" ';
            }
            $html .= 'data-folder-id="' . $folder->id . '"
                        data-folder-name="' . strtolower(htmlspecialchars($folder->name, ENT_QUOTES)) . '"
                        data-type="folder"
                        data-modified="' . $folder->updated_at . '"
                        data-level="' . $level . '">';

            $html .= '<td class="checkbox-cell" onclick="event.stopPropagation()">
                        <input type="checkbox"
                            class="item-checkbox form-check-input"
                            data-type="folder"
                            data-id="' . $folder->id . '"
                            data-name="' . htmlspecialchars($folder->name, ENT_QUOTES) . '"
                            onchange="handleFolderCheckbox(this)">
                    </td>';

            $html .= '<td class="folder-name-cell" data-folder-url="' . url('documents/folder/' . $folder->id) . '" onclick="handleFolderClick(this, ' . ($hasChildren ? 'true' : 'false') . ')">';
            $html .= '<div class="name-cell">';
            if ($level > 0) {
                $html .= '<span class="folder-indent" style="width: ' . ($level * 24) . 'px;"></span>';
            }
            if ($hasChildren) {
                $html .= '<span class="folder-toggle"><i class="ri-arrow-right-s-line"></i></span>';
            } else {
                $html .= '<span style="width: 20px; display: inline-block;"></span>';
            }
            $html .= '<i class="ri-folder-2-fill item-icon"></i>';
            $html .= '<span class="item-name">' . htmlspecialchars($folder->name) . '</span>';
            $html .= '</div></td>';
            $html .= '<td>Folder</td>';
            $html .= '<td>—</td>';
            $html .= '<td>—</td>';
            $html .= '<td>' . date('M d, Y', strtotime($folder->updated_at)) . '</td>';
            $html .= '<td class="actions-cell">
                        <div class="dropdown">
                            <button class="action-btn" data-bs-toggle="dropdown" onclick="event.stopPropagation()">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu">';

            if ($canEdit) {
                $html .= '<li>
                            <a class="dropdown-item" href="javascript:void(0)"
                                data-bs-toggle="modal"
                                data-bs-target="#renameFolderModal' . $folder->id . '">
                                <i class="ri-pencil-line me-2"></i>Rename folder
                            </a>
                        </li>';
            }
            if ($canDelete) {
                $html .= '<li>
                            <a class="dropdown-item text-danger delete-folder-btn" href="javascript:void(0)"
                                data-id="' . $folder->id . '"
                                data-name="' . htmlspecialchars($folder->name, ENT_QUOTES) . '">
                                <i class="ri-delete-bin-line me-2"></i>Delete folder
                            </a>
                        </li>';
            }

            $html .= '      </ul>
                        </div>
                    </td>';
            $html .= '</tr>';

            if (count($childFolders) > 0) {
                $html .= $this->renderFolderTreeView($allFolders, $documents, $currentFolderId, $level + 1, $folder->id);
            }

            if (count($folderDocuments) > 0) {
                foreach ($folderDocuments as $doc) {
                    $fileInfo = $this->getDocumentFileInfo($doc);

                    $cleanFileName = $fileInfo['originalFileName']
                        ? $doc->title . ' - ' . $fileInfo['originalFileName']
                        : $doc->title;

                    $escapedDisplay = htmlspecialchars($cleanFileName, ENT_QUOTES);
                    $escapedForJs   = addslashes($escapedDisplay);

                    $html .= '<tr class="child-row document-row"
                                data-parent-id="' . $folder->id . '"
                                data-document-id="' . $doc->id . '"
                                data-level="' . ($level + 1) . '"
                                data-type="' . $fileInfo['fileType'] . '"
                                data-modified="' . $doc->updated_at . '"
                                onclick="window.open(\'' . url('/documents/view-document/' . $doc->id) . '\', \'_blank\')">';

                    $html .= '<td class="checkbox-cell" onclick="event.stopPropagation()">
                                <input type="checkbox"
                                    class="item-checkbox form-check-input"
                                    data-type="document"
                                    data-id="' . $doc->id . '"
                                    data-name="' . $escapedDisplay . '"
                                    onchange="handleFolderCheckbox(this)">
                            </td>';

                    $html .= '<td><div class="name-cell">';
                    $html .= '<span class="folder-indent" style="width: ' . (($level + 1) * 24) . 'px;"></span>';
                    $html .= '<span style="width: 20px; display: inline-block;"></span>';
                    $html .= '<i class="' . $fileInfo['iconClass'] . ' item-icon" style="color: #6b7280;"></i>';
                    $html .= '<span class="item-name">' . $escapedDisplay . '</span>';
                    $html .= '</div></td>';

                    $html .= '<td>' . strtoupper($fileInfo['fileType']) . '</td>';
                    $html .= '<td>' . date('M d, Y', strtotime($doc->updated_at)) . '</td>';
                    $html .= '<td class="actions-cell" onclick="event.stopPropagation()">
                        <div class="dropdown">
                            <button class="action-btn" data-bs-toggle="dropdown" onclick="event.stopPropagation()">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu">';

                    if ($canDelete) {
                        $html .= '<li>
                                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                                        onclick="event.stopPropagation(); deleteDocument(' . $doc->id . ', \'' . $escapedForJs . '\')">
                                        <i class="ri-delete-bin-line me-2"></i>Delete document
                                    </a>
                                </li>';
                    }

                    $html .= '  </ul>
                            </div>
                        </td>';
                    $html .= '</tr>';
                }
            }
        }

        return $html;
    }

    public function bulkDelete(Request $request)
    {
        if (!canDelete('documents')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $folderIds = array_filter(explode(',', $request->folder_ids ?? ''));
        $documentIds = array_filter(explode(',', $request->document_ids ?? ''));

        foreach ($folderIds as $id) {
            $folder = DocumentFolder::with('document.attachments')->find(trim($id));
            if ($folder) {
                foreach ($folder->document as $doc) {
                    foreach ($doc->attachments as $attachment) {
                        $filePath = public_path($attachment->attachment);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        $attachment->delete();
                    }
                    $doc->delete();
                }
                $folder->delete();
            }
        }

        foreach ($documentIds as $id) {
            $doc = Document::with('attachments')->find(trim($id));
            if ($doc) {
                foreach ($doc->attachments as $attachment) {
                    $filePath = public_path($attachment->attachment);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $attachment->delete();
                }
                $doc->delete();
            }
        }

        return response()->json(['success' => true, 'message' => 'Successfully Deleted']);
    }

    /**
     * Show the form for creating a new resource.
     */
   public function create($id = null)
    {
        $approvers = User::all();
        $document_types = DocumentType::get();
        $departments = Department::where('status', 1)->orderBy('name', 'asc')->get();
        $teams = Team::where('status', 1)->orderBy('name', 'asc')->get();

        $change_request = null;
        if ($id)
        {
            $change_request = ChangeRequest::with('supporting_documents','approvers.user')->findOrFail($id);
        }

        return view('documents.create', compact('approvers', 'document_types', 'departments', 'teams', 'change_request'));
    }

    public function signature($id) {
        
        $change_request = ChangeRequest::findOrFail($id);
        $approver_stamp = ApproverStamp::where('user_id', auth()->id())->first();

        return view('documents.signature-assignment',
            array(
                'change_request' => $change_request,
                'approver_stamp' => $approver_stamp,
            )
        );
    }

    public function previewNextControlCode(Request $request)
    {
        $docTypeId = $request->input('doc_type_id');
        $officeId = $request->input('office_id');

        if (!$docTypeId || !$officeId) {
            return response()->json(['preview' => null]);
        }

        $docType = DocumentType::find($docTypeId);
        $office = Team::find($officeId);

        if (!$docType || !$office) {
            return response()->json(['preview' => null]);
        }

        $year = date('Y');
        $base = 'MarSU-' . $office->name . '-' . $docType->code . '-' . $year . '-';
        $padLen = 4;

        $latest = Document::where('control_code', 'like', $base . '%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(control_code, "-", -1) AS UNSIGNED) DESC')
            ->first();

        $nextNumber = 1;
        if ($latest) {
            preg_match('/-(\d+)$/', $latest->control_code, $m);
            $nextNumber = isset($m[1]) ? ((int)$m[1] + 1) : 1;
        }

        $preview = $base . str_pad($nextNumber, $padLen, '0', STR_PAD_LEFT);

        return response()->json(['preview' => $preview, 'next_number' => $nextNumber]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!canCreate('documents')) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title'         => 'required|string|max:255',
            'attachments'   => 'required|array|min:1',
            'attachments.*' => 'required|file',
            'folder'        => 'nullable|exists:document_folders,id',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->file('attachments') as $file) {
                $document            = new Document;
                $document->title     = $request->title;
                $document->user_id   = auth()->user()->id;
                $document->folder_id = $request->folder ?: null;
                $document->save();

                $name = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('document_attachments/'), $name);

                $doc_attachment              = new DocumentAttachment;
                $doc_attachment->document_id = $document->id;
                $doc_attachment->attachment  = '/document_attachments/' . $name;
                $doc_attachment->type        = 'file';
                $doc_attachment->save();

                if ($document->folder_id) {
                    $this->propagateFolderShares($document->folder_id, $document->id);
                }
            }

            DB::commit();

            Alert::success('Successfully Uploaded')->persistent('Dismiss');
            return back();

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Cannot upload documents', ['error' => $e->getMessage()]);
            Alert::error('Error')->persistent('Dismiss');
            return back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $document = Document::findOrFail($id);

        return view('documents.view_document',
            array(
                'document' => $document,
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $document->title = $request->title;
        $document->version = $request->revision;
        $document->timestamps = false;
        $document->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }         
           
    public function destroy($id)
    {
        //
    }

    public function showPDF($id)
    {
        $attachment = DocumentAttachment::with('document')->findOrFail($id);
        $changeRequest = ChangeRequest::where('document_id', $attachment->document_id)->orderBy('id','desc')->first();
        
        $pdf = new \setasign\Fpdi\Fpdi();
        $newFile = str_replace(' ', '%20', $attachment->attachment);
        $fileContentData = file_get_contents(url($newFile));

        if($changeRequest) {
            $data = url('change-request/'.$changeRequest->document_id);
        }
        else {
            $data = url('document/'.$attachment->document_id);
        }
    
        try 
        {
            $pageCount = $pdf->setSourceFile(StreamReader::createByString($fileContentData));
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // $pdf->AddPage();
                $pdf->setSourceFile(StreamReader::createByString($fileContentData));
                $tplIdx = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($tplIdx);
                if($size[0] > $size[1])
                {
                    $pdf->AddPage('L', array($size[1],$size[0]));
                }
                else
                {
                    $pdf->AddPage('P', array($size[1],$size[0]));
                }
                
                // dd($size);
                $pdf->useTemplate($tplIdx);
                $pdf->SetFont('Arial');
                $pdf->SetTextColor(1, 0, 0);
                $pdf->SetXY(160, 5);
                $pdf->SetFontSize(8);
    
                if ($pageNo == $pageCount)
                {
                    $options = new QROptions([
                        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                    ]);
    
                    $qrCode = new QRCode($options);
                    $qrImageString = $qrCode->render($data);
    
                    $qrSize = 15;
                    $margin = 10;
    
                    $x = $size['width']  - $qrSize - $margin;
                    $y = $margin;
    
                    $pdf->Image($qrImageString, $x, $y, $qrSize, $qrSize, 'PNG');
                }
            }
            $pdf->Output();
        }
        catch ( \Exception $e )
        {
            return Redirect::to(url($newFile));
        }
    }

    public function changePublic(Request $request)
    {
        // dd($request->all());
        $document = Document::findOrfail($request->id);
        if($request->value == "true")
        {
            $document->public = 1;
        }
        else
        {
            $document->public = null;
        }
        $document->timestamps=false;
        $document->save();

        return "success";
    }

    public function upload(Request $request,$id)
    {
        $file = $request->file('file');
        $name = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path() . '/document_attachments/', $name);
        $file_name = '/document_attachments/' . $name;

        $doc_attachment = DocumentAttachment::findOrfail($id);
        $doc_attachment->attachment = $file_name;
        $doc_attachment->save();
        
        
        Alert::success('Successfully Uploaded')->persistent('Dismiss');
        return back();
    }

    public function audit(Request $request)
    {
        $departments = Department::get();
        $companies = Company::get();
        $document_types = DocumentType::orderBy('name','desc')->get();
        $search = $request->search;
        $department = $request->department;
       
        $documents = Document::get();
        $documents_filter = Document::query();
        if($request->department != null)
        {
            $documents_filter->where('department_id',$request->department);
        }
        if($request->search != null)
        {
            $documents_filter->where('control_code','like','%'.$request->search.'%')->orWhere('title','like','%'.$request->search.'%');
        }
        
        $obsoletes = Obsolete::get();

        $search = $request->search;
        $department = $request->department;
            $documents_na = $documents_filter->get();
        
            return view('documents',
            array(
                'documents' => $documents,
                'documents_na' => $documents_na,
                'obsoletes' => $obsoletes,
                'departments' => $departments,
                'companies' => $companies,
                'document_types' => $document_types,
                'search' => $search,
                'dep' => $department,
                )
            );
    }

    public function addFolder(Request $request)
    {
        // dd($request->all());
        $folder = new DocumentFolder;
        $folder->name = $request->name;
        $folder->user_id = auth()->user()->id;
        if ($request->has('folder_id')) {
            $folder->parent_id = $request->folder_id;
        }
        $folder->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    private function getDocumentFileInfo($doc)
    {
        $document = Document::with('attachments')->find($doc->id);
        $fileType = 'document';
        $previewClass = 'default-preview';
        $iconClass = 'ri-file-list-line';
        $badgeClass = '';
        $originalFileName = null;

        if ($document && $document->attachments->count() > 0) {
            $attachment = $document->attachments->first()->attachment;
            $extension = pathinfo($attachment, PATHINFO_EXTENSION);
            $fileType = strtolower($extension);

            $rawName = basename($attachment);
            $originalFileName = preg_replace('/^\d+_/', '', $rawName);

            switch ($fileType) {
                case 'pdf':
                    $previewClass = 'pdf-preview';
                    $iconClass = 'ri-file-pdf-line';
                    $badgeClass = 'pdf-badge';
                    break;
                case 'docx':
                case 'doc':
                    $previewClass = 'docx-preview';
                    $iconClass = 'ri-file-word-line';
                    $badgeClass = 'docx-badge';
                    break;
                case 'xlsx':
                case 'xls':
                    $previewClass = 'xlsx-preview';
                    $iconClass = 'ri-file-excel-line';
                    $badgeClass = 'xlsx-badge';
                    break;
            }
        }

        return [
            'fileType'         => $fileType,
            'previewClass'     => $previewClass,
            'iconClass'        => $iconClass,
            'badgeClass'       => $badgeClass,
            'originalFileName' => $originalFileName,
        ];
    }

    public function folderView(Request $request, $id)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $document_types = DocumentType::orderBy('name', 'desc')->get();

        $all_document_folders = DocumentFolder::where('user_id', auth()->user()->id)->get();

        $users = User::whereNull('status')->get();
        $controlCodes = ControlCode::with('documentType', 'department')
            ->where('status', 1)
            ->orderBy('code')
            ->get();
        $teams = Team::where('status', 1)->orderBy('name', 'asc')->get();

        $upload_folders = DocumentFolder::with('document', 'childrenFolder')
            ->where('user_id', auth()->user()->id)
            ->get();

        $existingDocuments = Document::with('document_type_list', 'document_tags')
            ->where('user_id', auth()->user()->id)
            ->selectRaw('
                MAX(id) as id,
                control_code,
                MAX(title) as title,
                MAX(category) as category,
                MAX(folder_id) as folder_id,
                MAX(other_category) as other_category,
                MAX(type_of_request) as type_of_request,
                MAX(office_id) as office_id,
                MAX(version) AS latest_revision,
                COUNT(*) AS upload_count
            ')
            ->groupBy('control_code')
            ->orderBy('control_code', 'asc')
            ->get();

        $allUserFolders = DocumentFolder::with('document', 'childrenFolder')
            ->where('user_id', auth()->id())
            ->get();

        $allUserDocuments = Document::where('user_id', auth()->id())
            ->orderBy('control_code', 'desc')
            ->get();

        $folderData = $allUserFolders->map(fn($f) => [
            'id' => $f->id,
            'name' => $f->name,
            'docs' => $f->document->map(function($d) {
                $fileInfo = $this->getDocumentFileInfo($d);
                $originalFileName = $fileInfo['originalFileName'];

                $label = $d->title;
                if ($originalFileName) {
                    $label .= ' - ' . $originalFileName;
                }

                return [
                    'id'    => $d->id,
                    'label' => $label,
                ];
            })->values(),
        ])->values();

        $shareTree = $this->buildShareTree($allUserFolders, $allUserDocuments);
        $shareOthersDocs = $allUserDocuments
            ->filter(fn($d) => is_null($d->folder_id))
            ->map(fn($d) => [
                'id'    => $d->id,
                'label' => $d->control_code . ' - ' . $d->title,
            ])
            ->values()
            ->toArray();

        $sharedData = compact(
            'document_types', 'users', 'folderData', 'shareTree',
            'shareOthersDocs', 'existingDocuments', 'controlCodes', 'teams',
            'upload_folders'
        );

        if ($id === 'others') {
            $documentsQuery = Document::with('change_requests', 'attachments')
                ->whereNull('folder_id')
                ->where('user_id', auth()->id());

            if ($search) {
                $documentsQuery->where(fn($q) => $q
                    ->where('title', 'like', '%' . $search . '%')
                    ->orWhere('control_code', 'like', '%' . $search . '%')
                );
            }

            $documents = $documentsQuery->orderBy('control_code', 'desc')->get()->map(function ($doc) {
                $info = $this->getDocumentFileInfo($doc);
                $doc->fileType = $info['fileType'];
                $doc->previewClass = $info['previewClass'];
                $doc->iconClass = $info['iconClass'];
                $doc->badgeClass = $info['badgeClass'];
                return $doc;
            });

            $paginatedItems = $this->paginateCollection($documents, $perPage);

            return view('documents.folder_view', array_merge($sharedData, [
                'folder_data' => (object)[
                    'id' => 'others',
                    'name' => 'Others',
                    'childrenFolder' => collect([]),
                    'document' => $documents,
                    'updated_at'=> now(),
                ],
                'document_folders' => $all_document_folders,
                'documents' => Document::all(),
                'folders' => $paginatedItems,
                'totalFolders' => 0,
                'totalDocuments' => $documents->count(),
                'totalItems' => $documents->count(),
                'is_others_folder' => true,
                'folderTreeHtml' => '',
                'breadcrumbs' => [],
            ]));
        }

        $folder_data = DocumentFolder::with([
            'document',
            'childrenFolder',
            'parent',
            'parent.parent',
            'parent.parent.parent',
            'parent.parent.parent.parent',
        ])->findOrFail($id);

        $breadcrumbs = [];
        $current = $folder_data;
        while ($current) {
            array_unshift($breadcrumbs, $current);
            $current = $current->parent ?? null;
        }

        $foldersQuery = DocumentFolder::where('parent_id', $id);
        $documentsQuery = Document::where('folder_id', $id);

        if ($search) {
            $foldersQuery->where('name', 'like', '%' . $search . '%');
            $documentsQuery->where(fn($q) => $q
                ->where('title', 'like', '%' . $search . '%')
                ->orWhere('control_code', 'like', '%' . $search . '%')
            );
        }

        $childFolders = $foldersQuery->orderBy('name', 'asc')->get();
        $childDocuments = $documentsQuery->orderBy('control_code', 'desc')->get()->map(function ($doc) {
            $info = $this->getDocumentFileInfo($doc);
            $doc->fileType = $info['fileType'];
            $doc->previewClass = $info['previewClass'];
            $doc->iconClass = $info['iconClass'];
            $doc->badgeClass = $info['badgeClass'];
            $doc->originalFileName = $info['originalFileName'];
            return $doc;
        });

        $folder_data->document = $folder_data->document->map(function ($doc) {
            $info = $this->getDocumentFileInfo($doc);
            $doc->fileType = $info['fileType'];
            $doc->previewClass = $info['previewClass'];
            $doc->iconClass = $info['iconClass'];
            $doc->badgeClass = $info['badgeClass'];
            return $doc;
        });

        $items = collect();
        foreach ($childFolders as $folder) {
            $items->push((object)[
                'id' => $folder->id,
                'name' => $folder->name,
                'type' => 'folder',
                'updated_at' => $folder->updated_at,
            ]);
        }
        foreach ($childDocuments as $doc) {
            $items->push((object)[
                'id' => $doc->id,
                'name' => $doc->originalFileName ?: $doc->title,
                'title' => $doc->title,
                'control_code' => $doc->control_code,
                'type' => 'document',
                'updated_at' => $doc->updated_at,
                'fileType' => $doc->fileType,
                'previewClass' => $doc->previewClass,
                'iconClass' => $doc->iconClass,
                'badgeClass' => $doc->badgeClass,
            ]);
        }

        $paginatedItems = $this->paginateCollection($items, $perPage);
        $documents = Document::all();
        $folderTreeHtml  = $this->renderFolderTreeView($all_document_folders, $documents, $folder_data->id, 0, $folder_data->id);

        return view('documents.folder_view', array_merge($sharedData, [
            'folder_data' => $folder_data,
            'document_folders' => $all_document_folders,
            'documents' => $documents,
            'folders' => $paginatedItems,
            'totalFolders' => $childFolders->count(),
            'totalDocuments' => $childDocuments->count(),
            'totalItems' => $childFolders->count() + $childDocuments->count(),
            'is_others_folder' => false,
            'folderTreeHtml' => $folderTreeHtml,
            'breadcrumbs' => $breadcrumbs,
        ]));
    }

    private function paginateCollection($items, $perPage)
    {
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $collection  = collect($items);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $collection->slice(($currentPage - 1) * $perPage, $perPage)->all(),
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );
    }

    public function renameFolder(Request $request, $id)
    {
        if (!canEdit('documents')) {
            abort(403, 'Unauthorized');
        }

        $folder = DocumentFolder::findOrFail($id);
        $folder->name = $request->name;
        $folder->save();

        Alert::success('Successfully Rename')->persistent('Dismiss');
        return back();
    }

    public function deleteFolder(Request $request, $id)
    {
        if (!canDelete('documents')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $folder = DocumentFolder::with('document', 'childrenFolder')->findOrFail($id);

        if (count($folder->document) > 0 || count($folder->childrenFolder) > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete folder because it contains files or subfolders.'
            ]);
        }

        $folder->delete();

        return response()->json(['success' => true, 'message' => 'Successfully Deleted']);
    }

    public function signaturePosition(Request $request)
    {
        $document_signature_position = DocumentSignaturePosition::with('user')
            ->where('user_id', $request->user_id)
            ->where('change_request_id', $request->change_request_id)
            ->get();

        return response()->json($document_signature_position);
    }

    public function editDateApproved(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $approver = RequestApprover::findOrFail($id);

            $history = new History;
            $history->change_request_id = $approver->change_request_id;
            if ($approver->date_approved) {
                $history->comment = "From: ".$approver->date_approved. "<br>" . "To: " . $request->date_approved;
            } else {
                $history->comment = "From: ".date("Y-m-d", strtotime($approver->updated_at)). "<br>" . "To: " . $request->date_approved;
            }
            $history->user_id = auth()->user()->id;
            $history->save();

            $approver->date_approved = $request->date_approved;
            $approver->save();

            $documents = Document::findOrFail($request->document_id);
            $request = $documents->change_requests->sortByDesc('id')->first();
            $approver = $request->approvers->first();
            
            $users = User::whereIn('id',[$request->user_id, $approver->user_id])->get();
            Mail::to($users)->send(new ApprovedDateEmail($documents,$approver));

            DB::commit();

            Alert::success('Successfully Saved')->persistent('Dismiss');
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Cannot edit approved date", $e->getMessage());

            Alert::error('Error')->persistent('Dismiss');
            return back();
        }
    }

    public function uploadDocumentFolder(Request $request)
    {
        $documents = Document::whereIn('id',$request->documents)->get();
        foreach($documents as $document)
        {
            $document->folder_id = $request->folder_id;
            $document->save();

            if ($request->folder_id) {
                $this->propagateFolderShares($request->folder_id, $document->id);
            }
        }

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function publicDocument(Request $request, $id)
    {
        $document = Document::with('user', 'document_tags', 'attachments')->findOrFail($id);

        return view('public.document', 
            array(
                'document' => $document
            )
        );
    }

    public function viewChangeRequest($id)
    {
        $change_request = ChangeRequest::with('user', 'approvers.user', 'supporting_documents')->findOrFail($id);

        $code = 'DOC-' . date('Y', strtotime($change_request->created_at)) . '-' . str_pad($change_request->id, 3, '0', STR_PAD_LEFT);
        $currentApprover = $change_request->approvers->sortBy('level')->firstWhere('status', 'Pending');
        $filename = $change_request->file ? basename($change_request->file) : null;

        $totalApprovers = $change_request->approvers->count();
        $signedApprovers = $change_request->approvers->where('status', 'Approved')->count();

        return view('public.change-request', compact(
            'change_request',
            'code',
            'currentApprover',
            'filename',
            'totalApprovers',
            'signedApprovers'
        ));
    }

    public function refreshTeam(Request $request)
    {
        // dd($request->all());
        $teams = Team::where('department_id', $request->department)->get();
        $options = "";
        foreach($teams as $team) {
            $options .= '<option value="'.$team->id.'">'.$team->name.'</option>';
        }
        
        return $options;
    }

    public function userView(Request $request)
    {
        $visitor = new DocumentVisitor;
        $visitor->user_id = auth()->id();
        $visitor->document_id = $request->document_id;
        $visitor->save();
        
        return back();
    }

    public function visitors(Request $request,$id)
    {
        // dd($request->all());
        $document = Document::with("visitor.user")->findOrFail($id);
        $totalUniqueVisitors = $document->visitor->unique('user_id')->count();
        $totalVisitors = $document->visitor->count();

        return view("documents.visitors",
            array(
                "document" => $document,
                "totalUniqueVisitors" => $totalUniqueVisitors,
                "totalVisitors" => $totalVisitors
            )
        );
    }

    public function getDocumentByControlCode(Request $request)
    {
        $code = $request->input('control_code');
        $latest = Document::where('control_code', $code)
            ->orderBy('version', 'desc')
            ->first();

        if (!$latest) {
            return response()->json(['found' => false]);
        }

        $nextRevision = ($latest->version ?? 0) + 1;

        return response()->json([
            'found' => true,
            'control_code' => $latest->control_code,
            'title' => $latest->title,
            'category' => $latest->category,
            'folder_id' => $latest->folder_id,
            'other' => $latest->other_category,
            'type_of_request' => $latest->type_of_request,
            'latest_revision' => $latest->version,
            'next_revision' => $nextRevision,
        ]);
    }

    public function share(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'share_type' => 'required|in:folder,document',
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $documentIds = [];
        $folderId = null;

        if ($request->share_type === 'folder') {
            $request->validate(['folder_id' => 'required|exists:document_folders,id']);
            $folderId = $request->folder_id;
            $documentIds = $this->getAllDocumentIdsInFolder($folderId);
        } else {
            $request->validate([
                'documents' => 'required|array',
                'documents.*' => 'exists:documents,id',
            ]);
            $documentIds = $request->documents;
        }

        if (empty($documentIds)) {
            Alert::error('No documents found to share.')->persistent('Dismiss');
            return back();
        }

        $alreadyShared = [];
        $newCount = 0;

        foreach ($request->users as $userId) {
            foreach ($documentIds as $docId) {
                $exists = ShareDocument::where('user_id', $userId)
                    ->where('document_id', $docId)
                    ->exists();

                if ($exists) {
                    $alreadyShared[] = $docId;
                    continue;
                }

                $share = new ShareDocument;
                $share->user_id = $userId;
                $share->document_id = $docId;
                $share->folder_id = $folderId;
                $share->shared_by = auth()->id();
                $share->save();
                $newCount++;
            }
        }

        if ($newCount > 0) {
            $msg = $newCount . ' document' . ($newCount !== 1 ? 's' : '') . ' shared successfully.';
            if (count($alreadyShared) > 0) {
                $msg .= ' ' . count($alreadyShared) . ' already shared (skipped).';
            }
            Alert::success($msg)->persistent('Dismiss');
        } else {
            Alert::warning('All selected documents were already shared with the selected users.')->persistent('Dismiss');
        }

        return back();
    }

    public function shareActivity(Request $request)
    {
        $type = $request->input('type');
        $id   = $request->input('id');
    
        $activity = [];
    
        if ($type === 'folder') {
            $folder = DocumentFolder::find($id);
            if (!$folder) return response()->json([]);
    
            $docIds = $this->getAllDocumentIdsInFolder((int) $id);
    
            $shareRecords = ShareDocument::with('user')
                ->whereIn('document_id', $docIds)
                ->whereNotNull('folder_id')
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique(function ($s) {
                    return $s->user_id . '_' . $s->created_at->format('Y-m-d');
                });
    
            foreach ($shareRecords as $record) {
                $userName = ($record->user && $record->user->name) ? $record->user->name : 'Someone';
                $activity[] = [
                    'icon' => 'ri-share-line',
                    'color' => '#3b82f6',
                    'text' => '<strong>' . htmlspecialchars($folder->name) . '</strong> was shared with <strong>' . htmlspecialchars($userName) . '</strong>',
                    'time' => $record->created_at->format('M d, Y \a\t g:i A'),
                ];
            }
    
            $firstShare = ShareDocument::whereIn('document_id', $docIds)
                ->whereNotNull('folder_id')
                ->orderBy('created_at', 'asc')
                ->first();
    
            if ($firstShare) {
                $laterDocs = Document::with('user')
                    ->whereIn('id', $docIds)
                    ->where('created_at', '>', $firstShare->created_at)
                    ->orderBy('created_at', 'desc')
                    ->get();
    
                foreach ($laterDocs as $doc) {
                    $uploaderName = ($doc->user && $doc->user->name) ? $doc->user->name : 'Someone';
                    $activity[] = [
                        'icon' => 'ri-file-add-line',
                        'color' => '#10b981',
                        'text' => '<strong>' . htmlspecialchars($uploaderName) . '</strong> added <strong>' . htmlspecialchars($doc->control_code . ' - ' . $doc->title) . '</strong>',
                        'time' => $doc->created_at->format('M d, Y \a\t g:i A'),
                    ];
                }
            }
    
            $activity[] = [
                'icon' => 'ri-folder-add-line',
                'color' => '#e67e22',
                'text' => 'Folder <strong>' . htmlspecialchars($folder->name) . '</strong> was created',
                'time' => $folder->created_at->format('M d, Y \a\t g:i A'),
            ];
    
        } else {
            $doc = Document::with('user', 'share_document.user')->find($id);
            if (!$doc) return response()->json([]);
    
            foreach ($doc->share_document->sortByDesc('created_at') as $record) {
                $userName = ($record->user && $record->user->name) ? $record->user->name : 'Someone';
                $activity[] = [
                    'icon' => 'ri-share-line',
                    'color' => '#3b82f6',
                    'text' => 'Shared with <strong>' . htmlspecialchars($userName) . '</strong>',
                    'time' => $record->created_at->format('M d, Y \a\t g:i A'),
                ];
            }
    
            $uploaderName = ($doc->user && $doc->user->name) ? $doc->user->name : 'Someone';
            $activity[] = [
                'icon' => 'ri-upload-line',
                'color' => '#8b5cf6',
                'text' => '<strong>' . htmlspecialchars($uploaderName) . '</strong> uploaded this document',
                'time' => $doc->created_at->format('M d, Y \a\t g:i A'),
            ];
        }
    
        usort($activity, function ($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
    
        return response()->json($activity);
    }

    private function getAllDocumentIdsInFolder(int $folderId): array
    {
        $folder = DocumentFolder::with(['document', 'childrenFolder'])->find($folderId);
        if (!$folder) return [];

        $ids = $folder->document->pluck('id')->toArray();

        foreach ($folder->childrenFolder as $child) {
            $ids = array_merge($ids, $this->getAllDocumentIdsInFolder($child->id));
        }

        return array_unique($ids);
    }

    private function propagateFolderShares($folderId, $newDocumentId)
    {
        if (!$folderId) return;

        $folderIds = [];
        $current = DocumentFolder::find($folderId);
        while ($current) {
            $folderIds[] = $current->id;
            $current = $current->parent_id ? DocumentFolder::find($current->parent_id) : null;
        }

        $userIds = ShareDocument::whereIn('folder_id', $folderIds)
            ->pluck('user_id')
            ->unique()
            ->toArray();

        foreach ($userIds as $userId) {
            $alreadyExists = ShareDocument::where('user_id', $userId)
                ->where('document_id', $newDocumentId)
                ->exists();

            if (!$alreadyExists) {
                $share = new ShareDocument;
                $share->user_id = $userId;
                $share->document_id = $newDocumentId;
                $share->folder_id = $folderId;
                $share->save();
            }
        }
    }

    public function shareFolder(Request $request)
    {
        $documentIds = $this->getAllDocumentIdsInFolder((int) $request->folder_id);

        if (empty($documentIds)) {
            return response()->json([]);
        }

        $share_access = ShareDocument::with('user')
            ->whereIn('document_id', $documentIds)
            ->get()
            ->unique('user_id')
            ->values();

        return response()->json($share_access);
    }

    public function shareDocument(Request $request)
    {
        // dd($request->all());
        $share_access = ShareDocument::with("user")->where("document_id", $request->document)->get();

        return response()->json($share_access);
    }

    public function sharedWithMe()
    {
        if (!canView('share_with_me.view')) {
            return view('pages.403-error');
        }

        $userId = auth()->id();

        $sharedDocIds = ShareDocument::where('user_id', $userId)
            ->pluck('document_id')
            ->toArray();

        if (empty($sharedDocIds)) {
            return view('documents.shared_with_me', ['groupedByDate' => collect()]);
        }

        $sharedDocs = Document::with(['attachments', 'user', 'share_document.user', 'folder'])
            ->whereIn('id', $sharedDocIds)
            ->get();

        $items          = collect();
        $addedFolderIds = [];
        $coveredDocIds  = [];

        $sharedFolderIds = ShareDocument::where('user_id', $userId)
            ->whereNotNull('folder_id')
            ->pluck('folder_id')
            ->unique()
            ->toArray();

        foreach ($sharedFolderIds as $sharedFolderId) {
            $folder = DocumentFolder::find($sharedFolderId);
            if (!$folder) continue;

            $topFolder = $folder;
            $check     = $folder;

            while ($check->parent_id) {
                $parentIsShared = ShareDocument::where('user_id', $userId)
                    ->where('folder_id', $check->parent_id)
                    ->exists();

                if ($parentIsShared) {
                    $check = DocumentFolder::find($check->parent_id);
                    $topFolder = $check;
                } else {
                    break;
                }
            }

            if (in_array($topFolder->id, $addedFolderIds)) continue;
            $addedFolderIds[] = $topFolder->id;

            $allUnderTop = $this->getAllDocumentIdsInFolder($topFolder->id);
            $coveredDocIds = array_merge($coveredDocIds, $allUnderTop);

            $rep = ShareDocument::where('user_id', $userId)
                ->whereIn('document_id', $allUnderTop)
                ->orderBy('created_at', 'asc')
                ->first();

            $repDoc = $rep ? Document::with('user')->find($rep->document_id) : null;

            $sharedUsers = ShareDocument::with('user')
                ->where('folder_id', $topFolder->id)
                ->get()
                ->unique('user_id')
                ->values()
                ->map(function ($s) {
                    $name = ($s->user && $s->user->name) ? $s->user->name : '';
                    $s->avatarColor = $this->avatarColor($name);
                    return $s;
                });

            $ownerName  = ($repDoc && $repDoc->user) ? $repDoc->user->name  : '—';
            $ownerEmail = ($repDoc && $repDoc->user) ? $repDoc->user->email : '';

            $items->push([
                'id' => $topFolder->id,
                'name' => $topFolder->name,
                'type' => 'folder',
                'ownerName' => $ownerName,
                'ownerEmail' => $ownerEmail,
                'ownerColor' => $this->avatarColor($ownerName),
                'sharedUsers' => $sharedUsers,
                'dateShared' => $rep ? $rep->created_at->format('M d, Y') : '—',
                'sortDate' => $rep ? $rep->created_at : now(),
                'iconClass' => 'ri-folder-2-fill',
                'previewClass' => 'folder-preview',
            ]);
        }

        foreach ($sharedDocs as $doc) {
            if (in_array($doc->id, $coveredDocIds)) continue;

            $fileInfo = $this->getDocumentFileInfo($doc);
            $shareRecord = ShareDocument::where('user_id', $userId)
                ->where('document_id', $doc->id)
                ->first();

            $sharedUsers = $doc->share_document
                ->filter(function ($s) use ($userId) {
                    return $s->user_id !== $userId;
                })
                ->values()
                ->map(function ($s) {
                    $name = ($s->user && $s->user->name) ? $s->user->name : '';
                    $s->avatarColor = $this->avatarColor($name);
                    return $s;
                });

            $ownerName  = ($doc->user && $doc->user->name)  ? $doc->user->name  : '—';
            $ownerEmail = ($doc->user && $doc->user->email) ? $doc->user->email : '';

            $items->push([
                'id' => $doc->id,
                'name' => $fileInfo['originalFileName'] 
                        ? $doc->title . ' - ' . $fileInfo['originalFileName'] 
                        : $doc->title,
                'type' => 'document',
                'ownerName' => $ownerName,
                'ownerEmail' => $ownerEmail,
                'ownerColor' => $this->avatarColor($ownerName),
                'sharedUsers' => $sharedUsers,
                'dateShared' => $shareRecord ? $shareRecord->created_at->format('M d, Y') : '—',
                'sortDate' => $shareRecord ? $shareRecord->created_at : now(),
                'iconClass' => $fileInfo['iconClass'],
                'previewClass' => $fileInfo['previewClass'],
            ]);
        }

        $items = $items->sortByDesc('sortDate')->unique('id')->values();

        $groupedByDate = $items->groupBy(function($item) {
            $date = $item['sortDate'];
            $now = now();
            if ($date->isToday()) return 'Today';
            if ($date->isYesterday()) return 'Yesterday';
            if ($date->greaterThanOrEqualTo($now->copy()->subDays(7))) return 'Last 7 days';
            if ($date->greaterThanOrEqualTo($now->copy()->subMonth())) return 'Last month';
            if ($date->year === $now->year) return 'Earlier this year';
            return 'Older';
        });

        return view('documents.shared_with_me', compact('groupedByDate'));
    }

    public function sharedWithMeFolderView(Request $request, $id)
    {
        $userId = auth()->id();

        $documentIdsInThisFolder = $this->getAllDocumentIdsInFolder((int) $id);

        $hasDirectAccess = ShareDocument::where('user_id', $userId)
            ->whereIn('document_id', $documentIdsInThisFolder)
            ->exists();

        $hasInheritedAccess = false;
        $folder = DocumentFolder::with([
            'document',
            'childrenFolder',
            'parent',
            'parent.parent',
            'parent.parent.parent',
            'parent.parent.parent.parent',
        ])->findOrFail($id);

        $parentCheck = $folder->parent;
        while ($parentCheck) {
            $parentDocIds = $this->getAllDocumentIdsInFolder($parentCheck->id);
            $hasInheritedAccess = ShareDocument::where('user_id', $userId)
                ->whereIn('document_id', $parentDocIds)
                ->exists();
            if ($hasInheritedAccess) break;
            $parentCheck = isset($parentCheck->parent) ? $parentCheck->parent : null;
        }

        if (!$hasDirectAccess && !$hasInheritedAccess) {
            abort(403, 'You do not have access to this folder.');
        }

        $breadcrumbs = [];
        $current = $folder;
        while ($current) {
            array_unshift($breadcrumbs, $current);
            $current = isset($current->parent) ? $current->parent : null;
        }
    
        $sharedDocumentIds = ShareDocument::where('user_id', $userId)
            ->pluck('document_id')
            ->toArray();
    
        $childFolders = DocumentFolder::where('parent_id', $id)
            ->orderBy('name', 'asc')
            ->get();
    
        $childDocuments = Document::with(['attachments', 'user'])
            ->where('folder_id', $id)
            ->whereIn('id', $sharedDocumentIds)
            ->orderBy('control_code', 'desc')
            ->get()
            ->map(function($doc) {
                $fileInfo = $this->getDocumentFileInfo($doc);
                $doc->fileType = $fileInfo['fileType'];
                $doc->previewClass = $fileInfo['previewClass'];
                $doc->iconClass = $fileInfo['iconClass'];
                $doc->badgeClass = $fileInfo['badgeClass'];
                $doc->ownerName = ($doc->user && $doc->user->name) ? $doc->user->name : '—';
                $doc->ownerColor = $this->avatarColor($doc->ownerName);
                $doc->displayName = $fileInfo['originalFileName']
                    ? $doc->title . ' - ' . $fileInfo['originalFileName']
                    : $doc->title;
                return $doc;
            });
    
        $folderOwnerName  = '—';
        $folderOwnerColor = '#9ca3af';
    
        $topSharedFolderId = $id;
        $checkFolder = $folder;
        while ($checkFolder && $checkFolder->parent_id) {
            $parentIsShared = ShareDocument::where('user_id', $userId)
                ->where('folder_id', $checkFolder->parent_id)
                ->exists();
            if ($parentIsShared) {
                $checkFolder = DocumentFolder::find($checkFolder->parent_id);
                $topSharedFolderId = $checkFolder ? $checkFolder->id : $topSharedFolderId;
            } else {
                break;
            }
        }

        $breadcrumbs = [];
        $current = $folder;
        while ($current) {
            array_unshift($breadcrumbs, $current);
            if ($current->id == $topSharedFolderId) break;
            $current = isset($current->parent) ? $current->parent : null;
        }
    
        if ($folderOwnerName === '—' && $childDocuments->isNotEmpty()) {
            $first = $childDocuments->first();
            if ($first->ownerName !== '—') {
                $folderOwnerName  = $first->ownerName;
                $folderOwnerColor = $first->ownerColor;
            }
        }
    
        $allUnderFolder = $this->getAllDocumentIdsInFolder((int) $id);
        $topFolderId = $id;
    
        $checkFolder = $folder;
        while ($checkFolder && $checkFolder->parent_id) {
            $parentIsShared = ShareDocument::where('user_id', $userId)
                ->where('folder_id', $checkFolder->parent_id)
                ->exists();
            if ($parentIsShared) {
                $checkFolder = DocumentFolder::find($checkFolder->parent_id);
                $topFolderId = $checkFolder ? $checkFolder->id : $topFolderId;
            } else {
                break;
            }
        }
    
        $folderSharedUsers = ShareDocument::with('user')
            ->where('folder_id', $topFolderId)
            ->get()
            ->unique('user_id')
            ->filter(function ($s) use ($userId) {
                return $s->user_id !== $userId;
            })
            ->values()
            ->map(function ($s) {
                $name = ($s->user && $s->user->name) ? $s->user->name : '';
                $s->avatarColor = $this->avatarColor($name);
                return $s;
            });
    
        $folderSharedWithNames = $folderSharedUsers->pluck('user.name')->filter()->implode(', ');
    
        return view('documents.shared_with_me_folder', compact(
            'folder',
            'breadcrumbs',
            'childFolders',
            'childDocuments',
            'folderOwnerName',
            'folderOwnerColor',
            'folderSharedUsers',
            'folderSharedWithNames'
        ));
    }

    public function leaveShareFolder(Request $request)
    {
        $documentIds = $this->getAllDocumentIdsInFolder((int) $request->folder_id);

        ShareDocument::whereIn('document_id', $documentIds)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json(['success' => true, 'message' => 'You have left this shared folder.']);
    }

    private function avatarColor($name): string
    {
        $colors = ['#e74c3c','#3498db','#2ecc71','#9b59b6','#e67e22','#1abc9c','#e91e63','#607d8b'];
        return $colors[crc32($name) % count($colors)];
    }

    private function buildShareTree($folders, $documents, $parentId = null): array
    {
        $result = [];
        foreach ($folders->where('parent_id', $parentId) as $folder) {
            $result[] = [
                'id'       => $folder->id,
                'name'     => $folder->name,
                'children' => $this->buildShareTree($folders, $documents, $folder->id),
                'docs' => $documents->where('folder_id', $folder->id)->map(function($d) {
                    $fileInfo = $this->getDocumentFileInfo($d);
                    $originalFileName = $fileInfo['originalFileName'];

                    $label = $d->title;
                    if ($originalFileName) {
                        $label .= ' - ' . $originalFileName;
                    }

                    return [
                        'id' => $d->id,
                        'label' => $label,
                    ];
                })->values()->toArray(),
            ];
        }
        return $result;
    }

   public function sharedWithOthers()
    {
        if (!canView('share_with_others.view')) {
            return view('pages.403-error');
        }

        $userId = auth()->id();
        $me = User::find($userId);

        $ownerName  = $me ? $me->name  : '—';
        $ownerColor = $this->avatarColor($ownerName);

        $items          = collect();
        $addedFolderIds = [];
        $coveredDocIds  = [];

        $folderShareRecords = ShareDocument::where('shared_by', $userId)
            ->whereNotNull('folder_id')
            ->with('user')
            ->get();

        $sharedFolderIds = $folderShareRecords->pluck('folder_id')->unique()->toArray();

        foreach ($sharedFolderIds as $sharedFolderId) {
            $folder = DocumentFolder::find($sharedFolderId);
            if (!$folder) continue;

            if (in_array($folder->id, $addedFolderIds)) continue;
            $addedFolderIds[] = $folder->id;

            $allUnder = $this->getAllDocumentIdsInFolder($folder->id);
            $coveredDocIds = array_merge($coveredDocIds, $allUnder);

            $sharedUsers = ShareDocument::with('user')
                ->where('folder_id', $folder->id)
                ->get()
                ->unique('user_id')
                ->values()
                ->map(function ($share) {
                    $name = ($share->user && $share->user->name) ? $share->user->name : '';
                    $share->avatarColor = $this->avatarColor($name);
                    return $share;
                });

            $sharedWithNames = $sharedUsers->pluck('user.name')->filter()->implode(', ');

            $latestShare = ShareDocument::where('folder_id', $folder->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $items->push([
                'id' => $folder->id,
                'name' => $folder->name,
                'type' => 'folder',
                'ownerName' => $ownerName,
                'ownerColor' => $ownerColor,
                'sharedUsers' => $sharedUsers,
                'sharedWithNames'=> $sharedWithNames,
                'sortDate' => $latestShare ? $latestShare->created_at : $folder->updated_at,
                'dateLabel' => $latestShare ? $latestShare->created_at->format('M d, Y') : $folder->updated_at->format('M d, Y'),
                'iconClass' => 'ri-folder-2-fill',
                'previewClass' => 'folder-preview',
            ]);
        }

        $docShareRecords = ShareDocument::where('shared_by', $userId)
            ->whereNull('folder_id')
            ->pluck('document_id')
            ->unique()
            ->toArray();

        $individualDocIds = array_diff($docShareRecords, $coveredDocIds);

        $individualDocs = Document::with(['attachments', 'share_document.user'])
            ->whereIn('id', $individualDocIds)
            ->where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($individualDocs as $doc) {
            $fileInfo = $this->getDocumentFileInfo($doc);

            $sharedUsers = $doc->share_document
            ->filter(function ($share) {
                return is_null($share->folder_id);
            })
            ->map(function ($share) {
                $name = ($share->user && $share->user->name) ? $share->user->name : '';
                $share->avatarColor = $this->avatarColor($name);
                return $share;
            })->values();

            $sharedWithNames = $sharedUsers->pluck('user.name')->filter()->implode(', ');

            $latestShare = ShareDocument::where('document_id', $doc->id)
                ->whereNull('folder_id')
                ->orderBy('created_at', 'desc')
                ->first();

            $items->push([
                'id' => $doc->id,
                'name' => $fileInfo['originalFileName'] 
                        ? $doc->title . ' - ' . $fileInfo['originalFileName'] 
                        : $doc->title,
                'type' => 'document',
                'ownerName' => $ownerName,
                'ownerColor' => $ownerColor,
                'sharedUsers' => $sharedUsers,
                'sharedWithNames'=> $sharedWithNames,
                'sortDate' => $latestShare ? $latestShare->created_at : $doc->updated_at,
                'dateLabel' => $latestShare ? $latestShare->created_at->format('M d, Y') : $doc->updated_at->format('M d, Y'),
                'iconClass' => $fileInfo['iconClass'],
                'previewClass' => $fileInfo['previewClass'],
                'docId' => $doc->id,
                'docTitle' => $doc->title,
            ]);
        }

        $items = $items->sortByDesc('sortDate')->unique('id')->values();

        $groupedByDate = $items->groupBy(function ($item) {
            $date = $item['sortDate'];
            $now  = now();
            if ($date->isToday()) return 'Today';
            if ($date->isYesterday()) return 'Yesterday';
            if ($date->greaterThanOrEqualTo($now->copy()->subDays(7))) return 'Last 7 days';
            if ($date->greaterThanOrEqualTo($now->copy()->subMonth())) return 'Last month';
            if ($date->year === $now->year) return 'Earlier this year';
            return 'Older';
        });

        return view('documents.shared_with_others', compact('groupedByDate'));
    }

    public function sharedWithOthersFolderView(Request $request, $id)
    {
        $userId = auth()->id();
        $me = User::find($userId);
    
        $ownerName = $me ? $me->name : '—';
        $ownerColor = $this->avatarColor($ownerName);
    
        $folder = DocumentFolder::with([
            'document',
            'childrenFolder',
            'parent',
            'parent.parent',
            'parent.parent.parent',
        ])->findOrFail($id);
    
        $breadcrumbs = [];
        $current = $folder;
        while ($current) {
            array_unshift($breadcrumbs, $current);
            $current = isset($current->parent) ? $current->parent : null;
        }
    
        $childFolders = DocumentFolder::where('parent_id', $id)
            ->orderBy('name', 'asc')
            ->get();
    
        $childDocuments = Document::with(['attachments', 'share_document.user'])
            ->where('folder_id', $id)
            ->where('user_id', $userId)
            ->orderBy('control_code', 'desc')
            ->get()
            ->map(function ($doc) {
                $fileInfo = $this->getDocumentFileInfo($doc);   
                $doc->fileType = $fileInfo['fileType'];
                $doc->previewClass = $fileInfo['previewClass'];
                $doc->iconClass = $fileInfo['iconClass'];
                $doc->badgeClass = $fileInfo['badgeClass'];
                $doc->displayName = $fileInfo['originalFileName']
                    ? $doc->title . ' - ' . $fileInfo['originalFileName']
                    : $doc->title;
                return $doc;
            });
    
        $folderDocIds = $this->getAllDocumentIdsInFolder((int) $id);
        $sharedUsers = ShareDocument::with('user')
            ->whereIn('document_id', $folderDocIds)
            ->get()
            ->unique('user_id')
            ->values()
            ->map(function($share) {
                $name = ($share->user && $share->user->name) ? $share->user->name : '';
                $share->avatarColor = $this->avatarColor($name);
                return $share;
            });
    
        $sharedWithNames = $sharedUsers->pluck('user.name')->filter()->implode(', ');
    
        return view('documents.shared_with_others_folder', compact(
            'folder',
            'breadcrumbs',
            'childFolders',
            'childDocuments',
            'sharedUsers',
            'ownerName',
            'ownerColor',
            'sharedWithNames'
        ));
    }

    public function revokeAccess(Request $request)
    {
        $share = ShareDocument::where('document_id', $request->document_id)
            ->where('user_id', $request->user_id)
            ->first();

        if (!$share) {
            return response()->json(['success' => false, 'message' => 'Share record not found.']);
        }

        $document = Document::find($request->document_id);
        if (!$document || $document->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $share->delete();

        return response()->json(['success' => true, 'message' => 'Access revoked successfully.']);
    }

    public function revokeFolderAccess(Request $request)
    {
        $documentIds = $this->getAllDocumentIdsInFolder((int) $request->folder_id);

        if (empty($documentIds)) {
            return response()->json(['success' => false, 'message' => 'No documents found in folder.']);
        }

        $ownedCount = Document::whereIn('id', $documentIds)
            ->where('user_id', auth()->id())
            ->count();

        if ($ownedCount === 0) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        ShareDocument::whereIn('document_id', $documentIds)
            ->where('user_id', $request->user_id)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Folder access revoked successfully.']);
    }

    public function leaveShare(Request $request)
    {
        ShareDocument::where('document_id', $request->document_id)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json(['success' => true, 'message' => 'You have left this shared document.']);
    }
}