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
use App\DateApprovedLog;
use App\DocumentFolder;
use App\DocumentSignaturePosition;
use App\DocumentTag;
use App\Mail\ApprovedDateEmail;
use App\RequestApprover;
use App\User;
use App\Team;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
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
    // public function index(Request $request)
    // {
    //     $search = $request->search;
    //     $department = $request->department;

    //     $document_types = DocumentType::orderBy('name','desc')->get();
    //     $document_folders = DocumentFolder::with('document','childrenFolder')->where('parent_id',null)->get();
    //     $obsoletes = Obsolete::get();

    //     $documents = Document::with('change_requests','attachments')->orderBy('control_code','desc')->get();
    //     if (auth()->user()->role != "Administrator")
    //     {
    //         $documents = Document::with('change_requests','attachments')->where('user_id', auth()->user()->id)->orderBy('control_code','desc')->get();
    //     }

    //     // $documents_filter = Document::query();
     
    //     // if($request->department != null)
    //     // {
    //     //     $documents_filter = $documents_filter->where('department_id',$request->department);
            
    //     // }
    //     // if($request->search != null)
    //     // {
    //     //     $documents_filter = $documents_filter->where('control_code','like','%'.$request->search.'%')->orWhere('title','like','%'.$request->search.'%')->orWhere('old_control_code','like','%'.$request->search.'%');
           
    //     // }

    //     // if(auth()->user()->role == "Document Control Officer")
    //     // { 
    //     //     $documents = Document::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray())->get();
    //     //     $documents_filter = $documents_filter->whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray());
    //     //     $obsoletes = Obsolete::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->get();
    //     //     $departments = $departments->whereIn('id',(auth()->user()->dco)->pluck('department_id')->toArray());
                   
    //     // }
    //     // if(auth()->user()->role == "Documents and Records Controller")
    //     // { 
   
    //     //     $documents = Document::where('department_id',auth()->user()->department_id)->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray())->get();
    //     //     $documents_filter = $documents_filter->where('department_id',auth()->user()->department_id)->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray());
    //     //     $obsoletes = Obsolete::where('department_id',auth()->user()->department_id)->get();
    //     //     $departments = $departments->where('id',auth()->user()->department_id);
                   
    //     // }
        
    //     // if((auth()->user()->role == "Department Head"))
    //     // {
    //     //     $documents = Document::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray())->get();
    //     //     $documents_filter = $documents_filter->whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray());
    //     //     $obsoletes = Obsolete::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->get();
    //     //     $departments = $departments->whereIn('id',(auth()->user()->department_head)->pluck('id')->toArray());
           
          
    //     // }
    //     // if((auth()->user()->role == "User"))
    //     // {
    //     //     $documents = Document::where('department_id',auth()->user()->department_id)->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray())->get();
    //     //     $documents_filter = $documents_filter->where('department_id',auth()->user()->department_id)->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray());
    //     //     $obsoletes = Obsolete::where('department_id',auth()->user()->department_id)->get();
    //     //     $departments = $departments->where('id',auth()->user()->department_id);
       
    //     // }

    //     // $documents_na = $documents_filter->orderBy('control_code', 'asc')->get();
    //         // ->paginate(10);
        
 
    //     return view('documents.documents',
    //     array(
    //         'documents' => $documents,
    //         // 'documents_na' => $documents_na,
    //         'obsoletes' => $obsoletes,
    //         // 'departments' => $departments,
    //         // 'companies' => $companies,
    //         'document_types' => $document_types,
    //         'search' => $search,
    //         'dep' => $department,
    //         'document_folders' => $document_folders
    //         )
    //     );
    // }

    public function index(Request $request)
    {
        $search = $request->search;
        $department = $request->department;

        $document_types = DocumentType::orderBy('name','desc')->get();
        $document_folders = DocumentFolder::with('document','childrenFolder')->get();
        $obsoletes = Obsolete::get();

        $documents = Document::with('change_requests','attachments')->orderBy('control_code','desc')->get();
        if (auth()->user()->role != "Administrator")
        {
            $documents = Document::with('change_requests','attachments')->where('user_id', auth()->user()->id)->orderBy('control_code','desc')->get();
        }

        $document_folders = $document_folders->map(function($folder) {
            if ($folder->document) {
                $folder->document = $folder->document->map(function($doc) {
                    $fileInfo = $this->getDocumentFileInfo($doc);
                    $doc->fileType = $fileInfo['fileType'];
                    $doc->previewClass = $fileInfo['previewClass'];
                    $doc->iconClass = $fileInfo['iconClass'];
                    $doc->badgeClass = $fileInfo['badgeClass'];
                    return $doc;
                });
            }
            return $folder;
        });

        $documents = $documents->map(function($doc) {
            $fileInfo = $this->getDocumentFileInfo($doc);
            $doc->fileType = $fileInfo['fileType'];
            $doc->previewClass = $fileInfo['previewClass'];
            $doc->iconClass = $fileInfo['iconClass'];
            $doc->badgeClass = $fileInfo['badgeClass'];
            return $doc;
        });

        $allFolders = $document_folders->where('parent_id', null);
        $hasOthers = $documents->where('folder_id', null)->count() > 0;
        $totalFolders = $allFolders->count() + ($hasOthers ? 1 : 0);

        $folderTreeHtml = $this->renderFolderTreeView($document_folders, $documents, null, 0, null);

        return view('documents.documents',
            array(
                'documents' => $documents,
                'obsoletes' => $obsoletes,
                'document_types' => $document_types,
                'search' => $search,
                'dep' => $department,
                'document_folders' => $document_folders,
                'totalFolders' => $totalFolders,
                'allFolders' => $allFolders,
                'hasOthers' => $hasOthers,
                'folderTreeHtml' => $folderTreeHtml
            )
        );
    }

    private function renderFolderTreeView($allFolders, $documents, $currentFolderId, $level = 0, $parentId = null) {
        $html = '';
        $filteredFolders = $allFolders->where('parent_id', $parentId);
        
        foreach ($filteredFolders as $folder) {
            $childFolders = $allFolders->where('parent_id', $folder->id);
            $folderDocuments = $documents->where('folder_id', $folder->id);
            $hasChildren = count($folderDocuments) > 0 || count($childFolders) > 0;
            
            $rowClass = 'folder-tree-row document-row ' . ($hasChildren ? 'has-children' : '');
            if ($level > 0) {
                $rowClass = 'child-row folder-tree-row document-row ' . ($hasChildren ? 'has-children' : '');
            }
            
            $html .= '<tr class="' . $rowClass . ' demoTableRow" ';
            if ($level > 0) {
                $html .= 'data-parent-id="' . $parentId . '" ';
            }
            $html .= 'data-folder-id="' . $folder->id . '" 
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
            
            $html .= '<td class="folder-name-cell" data-folder-url="' . url('documents/folder/'.$folder->id) . '" onclick="handleFolderClick(this, ' . ($hasChildren ? 'true' : 'false') . ')">';
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
            $html .= '<span class="item-name">' . $folder->name . '</span>';
            $html .= '</div></td>';
            $html .= '<td>Folder</td>';
            $html .= '<td>—</td>';
            $html .= '<td>' . date('M d, Y', strtotime($folder->updated_at)) . '</td>';
            $html .= '<td class="actions-cell" onclick="event.stopPropagation()">
                <div class="dropdown">
                    <button class="action-btn" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-danger delete-folder-btn" href="javascript:void(0)" 
                            data-id="' . $folder->id . '" data-name="' . htmlspecialchars($folder->name, ENT_QUOTES) . '">
                            <i class="ri-delete-bin-line me-2"></i>Delete folder
                        </a></li>
                    </ul>
                </div>
            </td>';
            $html .= '</tr>';
            
            if (count($childFolders) > 0) {
                $html .= $this->renderFolderTreeView($allFolders, $documents, $currentFolderId, $level + 1, $folder->id);
            }
            
            if (count($folderDocuments) > 0) {
                foreach ($folderDocuments as $doc) {
                    $fileInfo = $this->getDocumentFileInfo($doc);
                    
                    $html .= '<tr class="child-row document-row"
                                data-parent-id="' . $folder->id . '"
                                data-document-id="' . $doc->id . '"
                                data-level="' . ($level + 1) . '"
                                data-type="' . $fileInfo['fileType'] . '"
                                data-modified="' . $doc->updated_at . '"
                                onclick="window.open(\'' . url('/documents/view-document/'.$doc->id) . '\', \'_blank\')">';
                    $html .= '<td class="checkbox-cell" onclick="event.stopPropagation()">
                                <input type="checkbox" 
                                    class="item-checkbox form-check-input" 
                                    data-type="document" 
                                    data-id="' . $doc->id . '" 
                                    data-name="' . htmlspecialchars($doc->control_code . ' - ' . $doc->title, ENT_QUOTES) . '"
                                    onchange="handleFolderCheckbox(this)">
                              </td>';
                    $html .= '<td><div class="name-cell">';
                    $html .= '<span class="folder-indent" style="width: ' . (($level + 1) * 24) . 'px;"></span>';
                    $html .= '<span style="width: 20px; display: inline-block;"></span>';
                    $html .= '<i class="' . $fileInfo['iconClass'] . ' item-icon" style="color: #6b7280;"></i>';
                    $html .= '<span class="item-name">' . $doc->control_code . ' - ' . $doc->title . '</span>';
                    $html .= '</div></td>';
                    $html .= '<td>' . strtoupper($fileInfo['fileType']) . '</td>';
                    $html .= '<td>—</td>';
                    $html .= '<td>' . date('M d, Y', strtotime($doc->updated_at)) . '</td>';
                    $html .= '<td class="actions-cell" onclick="event.stopPropagation()">
                        <div class="dropdown">
                            <button class="action-btn" data-bs-toggle="dropdown"><i class="ri-more-2-fill"></i></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item text-danger delete-folder-btn" href="javascript:void(0)" 
                                    data-id="' . $folder->id . '" data-name="' . htmlspecialchars($folder->name, ENT_QUOTES) . '">
                                    <i class="ri-delete-bin-line me-2"></i>Delete folder
                                </a></li>
                            </ul>
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
        $folderIds   = array_filter(explode(',', $request->folder_ids ?? ''));
        $documentIds = array_filter(explode(',', $request->document_ids ?? ''));

        foreach ($folderIds as $id) {
            $folder = DocumentFolder::with('document')->find(trim($id));
            if ($folder) {
                Document::where('folder_id', $folder->id)->delete();
                $folder->delete();
            }
        }

        foreach ($documentIds as $id) {
            $doc = Document::find(trim($id));
            if ($doc) {
                $doc->delete();
            }
        }

        return response()->json(['success' => true, 'message' => 'Successfully Deleted']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function create($id = null)
    {
        $approvers = User::all();
        $document_types = DocumentType::get();
        $departments = Department::where('status', null)->orderBy('name', 'asc')->get();
        $teams = Team::where('status', null)->orderBy('name', 'asc')->get();

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // dd($request->all());
        $request->validate([
            'control_code' => 'unique:documents,control_code'
        ]);

        $document = new Document;
        $document->control_code = $request->control_code;
        $document->title = $request->title;
        // $document->company_id = $request->company;
        // $document->department_id = $request->department;
        $document->category = $request->document_type;
        $document->other_category = $request->other;
        $document->effective_date = $request->effective_date;
        $document->user_id = auth()->user()->id;
        $document->version = $request->version;
        $document->public = $request->public;
        $document->folder_id = $request->folder;
        $document->save();

        foreach($request->file('attachment') as $key => $file)
        {
            $name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path() . '/document_attachments/', $name);
            $file_name = '/document_attachments/' . $name;

            $doc_attachment = new DocumentAttachment;
            $doc_attachment->document_id = $document->id;
            $doc_attachment->attachment = $file_name;
            $doc_attachment->type = $key;
            $doc_attachment->save();
            
        }

        foreach($request->tags as $tag)
        {
            $document_tag = new DocumentTag;
            $document_tag->document_id = $document->id;
            $document_tag->name = $tag;
            $document_tag->save();
        }   

        Alert::success('Successfully Uploaded')->persistent('Dismiss');
        return back();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = Document::findOrfail($id);

        return view('documents.view_document',
            array(
                'document' => $document,
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        //

        $document = Document::findOrFail($id);
        $document->title = $request->title;
        $document->version = $request->revision;
        
        // Temporarily disable timestamps
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    // public function showPDF($id)
    // {
    //     ini_set('memory_limit', '-1');
    //     $attachment = DocumentAttachment::with('document')->findOrFail($id);
    //         $pdf = new \setasign\Fpdi\Fpdi();
    //         $newFile = str_replace(' ', '%20', $attachment->attachment);
          
    //             $fileContentData = file_get_contents(url($newFile));
    //             try {
                    
    //                 $pageCount = $pdf->setSourceFile(StreamReader::createByString($fileContentData));
    //                 for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    //                         // $pdf->AddPage();
    //                         $pdf->setSourceFile(StreamReader::createByString($fileContentData));
    //                         $tplIdx = $pdf->importPage($pageNo);
    //                         $size = $pdf->getTemplateSize($tplIdx);
    //                         if($size[0] > $size[1])
    //                         {
    //                             $pdf->AddPage('L', array($size[1],$size[0]));
    //                         }
    //                         else
    //                         {
    //                             $pdf->AddPage('P', array($size[1],$size[0]));
    //                         }
                           
    //                         // dd($size);
    //                         $pdf->useTemplate($tplIdx);
    //                         $pdf->SetFont('Arial');
    //                         $pdf->SetTextColor(1, 0, 0);
    //                         $pdf->SetXY(160, 5);
    //                         $pdf->SetFontSize(8);
    //                         if($pageNo == 1)
    //                         {
    //                             $pdf->Write(1, "Effective Date: ".date("m/d/Y",strtotime($attachment->document->updated_at))); 
    //                         }
                           
    //                         $pdf->Image('images/uncontrolled.png', 15, 100, 200, '', '', '', '', false, 300);
    //                 }
    //                 $pdf->Output();
    //             }
    //             catch ( \Exception $e )
    //             {
    //                 return Redirect::to(url($newFile));
    //             }
              
           
        
      
    // }

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
                    // Generate QR code
                    $options = new QROptions([
                        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                    ]);
    
                    $qrCode = new QRCode($options);
                    $qrImageString = $qrCode->render($data);
    
                    // Bottom-right position
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
        if($request->has('folder_id'))
        {
            $folder->parent_id = $request->folder_id;
        }
        $folder->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    // public function folderView(Request $request,$id)
    // {
    //     $folder_data = DocumentFolder::with('document','childrenFolder')->findOrFail($id);
    //     $documents = Document::get();

    //     $document_folders = DocumentFolder::get();

    //     return view('documents.folder_view',
    //         array(
    //             'folder_data' => $folder_data,
    //             'document_folders' => $document_folders,
    //             'documents' => $documents
    //         )
    //     );
    // }

    private function getDocumentFileInfo($doc)
    {
        $document = Document::with('attachments')->find($doc->id);
        $fileType = 'document';
        $previewClass = 'default-preview';
        $iconClass = 'ri-file-list-line';
        $badgeClass = '';
        
        if ($document && $document->attachments->count() > 0) {
            $attachment = $document->attachments->first()->attachment;
            $extension = pathinfo($attachment, PATHINFO_EXTENSION);
            $fileType = strtolower($extension);
            
            switch($fileType) {
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
            'fileType' => $fileType,
            'previewClass' => $previewClass,
            'iconClass' => $iconClass,
            'badgeClass' => $badgeClass
        ];
    }

    public function folderView(Request $request, $id)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        
        $document_types = DocumentType::orderBy('name','desc')->get();
        $all_document_folders = DocumentFolder::get();

        $is_others_folder = ($id === 'others');

        $breadcrumbs = [];

        if ($id === 'others') {
            $documentsQuery = Document::with('change_requests','attachments')
                ->whereNull('folder_id');
            
            if (auth()->user()->role != "Administrator") {
                $documentsQuery->where('user_id', auth()->user()->id);
            }

            if ($search) {
                $documentsQuery->where(function($query) use ($search) {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhere('control_code', 'like', '%'.$search.'%');
                });
            }

            $documents = $documentsQuery->orderBy('control_code', 'desc')->get();

            $documentsWithFileInfo = $documents->map(function($doc) {
                $fileInfo = $this->getDocumentFileInfo($doc);
                $doc->fileType = $fileInfo['fileType'];
                $doc->previewClass = $fileInfo['previewClass'];
                $doc->iconClass = $fileInfo['iconClass'];
                $doc->badgeClass = $fileInfo['badgeClass'];
                return $doc;
            });

            $items = $documentsWithFileInfo->map(function($doc) {
                return (object)[
                    'id' => $doc->id,
                    'name' => $doc->control_code . ' - ' . $doc->title,
                    'title' => $doc->title,
                    'control_code' => $doc->control_code,
                    'type' => 'document',
                    'updated_at' => $doc->updated_at,
                    'fileType' => $doc->fileType,
                    'previewClass' => $doc->previewClass,
                    'iconClass' => $doc->iconClass,
                    'badgeClass' => $doc->badgeClass,
                ];
            });

            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $itemCollection = collect($items);
            $currentPageItems = $itemCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentPageItems,
                $itemCollection->count(),
                $perPage,
                $currentPage,
                ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
            );

            return view('documents.folder_view',
                array(
                    'folder_data' => (object)[
                        'id' => 'others',
                        'name' => 'Others',
                        'childrenFolder' => collect([]),
                        'document' => $documentsWithFileInfo,
                        'updated_at' => now()
                    ],
                    'document_folders' => $all_document_folders,
                    'documents' => Document::all(),
                    'folders' => $paginatedItems,
                    'totalFolders' => 0,
                    'totalDocuments' => $documents->count(),
                    'totalItems' => $documents->count(),
                    'is_others_folder' => true,
                    'document_types' => $document_types,
                    'folderTreeHtml' => '',
                    'breadcrumbs' => []
                )
            );
        }

        $folder_data = DocumentFolder::with([
            'document',
            'childrenFolder',
            'parent',
            'parent.parent',
            'parent.parent.parent',
            'parent.parent.parent.parent'
        ])->findOrFail($id);
        
        $current = $folder_data;
        while($current) {
            array_unshift($breadcrumbs, $current);
            $current = $current->parent ?? null;
        }
        
        $foldersQuery = DocumentFolder::where('parent_id', $id);
        $documentsQuery = Document::where('folder_id', $id);

        if ($search) {
            $foldersQuery->where('name', 'like', '%'.$search.'%');
            $documentsQuery->where(function($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%')
                    ->orWhere('control_code', 'like', '%'.$search.'%');
            });
        }

        $childFolders = $foldersQuery->orderBy('name', 'asc')->get();
        $childDocuments = $documentsQuery->orderBy('control_code', 'desc')->get();

        $documentsWithFileInfo = $childDocuments->map(function($doc) {
            $fileInfo = $this->getDocumentFileInfo($doc);
            $doc->fileType = $fileInfo['fileType'];
            $doc->previewClass = $fileInfo['previewClass'];
            $doc->iconClass = $fileInfo['iconClass'];
            $doc->badgeClass = $fileInfo['badgeClass'];
            return $doc;
        });

        $folder_data->document = $folder_data->document->map(function($doc) {
            $fileInfo = $this->getDocumentFileInfo($doc);
            $doc->fileType = $fileInfo['fileType'];
            $doc->previewClass = $fileInfo['previewClass'];
            $doc->iconClass = $fileInfo['iconClass'];
            $doc->badgeClass = $fileInfo['badgeClass'];
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
        
        foreach ($documentsWithFileInfo as $doc) {
            $items->push((object)[
                'id' => $doc->id,
                'name' => $doc->control_code . ' - ' . $doc->title,
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

        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $items->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $items->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );

        $documents = Document::all();
        
        $totalFolders = $childFolders->count();
        $totalDocuments = $childDocuments->count();
        $totalItems = $totalFolders + $totalDocuments;

        $folderTreeHtml = $this->renderFolderTreeView($all_document_folders, $documents, $folder_data->id, 0, $folder_data->id);

        return view('documents.folder_view',
            array(
                'folder_data' => $folder_data,
                'document_folders' => $all_document_folders,
                'documents' => $documents,
                'folders' => $paginatedItems,
                'totalFolders' => $totalFolders,
                'totalDocuments' => $totalDocuments,
                'totalItems' => $totalItems,
                'is_others_folder' => false,
                'document_types' => $document_types,
                'folderTreeHtml' => $folderTreeHtml,
                'breadcrumbs' => $breadcrumbs
            )
        );
    }

    public function renameFolder(Request $request,$id)
    {
        $folder = DocumentFolder::findOrFail($id);
        $folder->name = $request->name;
        $folder->save();

        Alert::success('Successfully Rename')->persistent('Dismiss');
        return back();
    }

    public function deleteFolder(Request $request, $id)
    {
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
    public function editDateApproved(Request $request,$id)
    {
        // dd($request->all());
        $approver = RequestApprover::findOrFail($id);
        $approver->date_approved = $request->date_approved." ".date('H:i:s');
        $approver->save();

        $logs = new DateApprovedLog;
        $logs->user_id = auth()->id();
        $logs->date_approved = $request->date_approved." ".date('H:i:s');
        $logs->change_request_id = $approver->change_request_id;
        $logs->save();

        // $documents = Document::findOrFail($request->document_id);
        // $request = $documents->change_requests->sortByDesc('id')->first();
        // $approver = $request->approvers->first();
        
        // $users = User::whereIn('id',[$request->user_id, $approver->user_id])->get();
        // Mail::to($users)->send(new ApprovedDateEmail($documents,$approver));

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }
    public function uploadDocumentFolder(Request $request)
    {
        $documents = Document::whereIn('id',$request->documents)->get();
        foreach($documents as $document)
        {
            $document->folder_id = $request->folder_id;
            $document->save();
        }

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }
    
    public function publicDocument(Request $request,$id)
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
        
        return view('public.change-request', compact('change_request'));
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
}