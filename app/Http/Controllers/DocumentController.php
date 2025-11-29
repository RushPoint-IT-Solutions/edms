<?php

namespace App\Http\Controllers;

use App\ChangeRequest;
use App\Document;
use App\Obsolete;
use App\Department;
use App\DocumentType;
use App\DocumentAttachment;
use App\Company;
use App\DocumentFolder;
use App\DocumentTag;
use App\User;
use Illuminate\Http\Request;
use \setasign\Fpdi\PdfParser\StreamReader;
use \setasign\Fpdi\PdfParser\CrossReference;
use Illuminate\Support\Facades\Redirect;

use RealRashid\SweetAlert\Facades\Alert;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function api()
    {
        $request_documents = Document::with('attachments')->where('public','!=',null)->where('status',null)->get();

        return response()->json($request_documents,200);
    }
    public function index(Request $request)
    {
        //
        // $departments = Department::get();
        // $companies = Company::get();
        $document_types = DocumentType::orderBy('name','desc')->get();
        $search = $request->search;
        $department = $request->department;
        $documents = Document::with('change_requests','attachments')->orderBy('control_code','desc')->get();
        $document_folders = DocumentFolder::with('document')->get();
        $obsoletes = Obsolete::get();

        // $documents_filter = Document::query();
     
        // if($request->department != null)
        // {
        //     $documents_filter = $documents_filter->where('department_id',$request->department);
            
        // }
        // if($request->search != null)
        // {
        //     $documents_filter = $documents_filter->where('control_code','like','%'.$request->search.'%')->orWhere('title','like','%'.$request->search.'%')->orWhere('old_control_code','like','%'.$request->search.'%');
           
        // }

        // if(auth()->user()->role == "Document Control Officer")
        // { 
        //     $documents = Document::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray())->get();
        //     $documents_filter = $documents_filter->whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray());
        //     $obsoletes = Obsolete::whereIn('department_id',(auth()->user()->dco)->pluck('department_id')->toArray())->get();
        //     $departments = $departments->whereIn('id',(auth()->user()->dco)->pluck('department_id')->toArray());
                   
        // }
        // if(auth()->user()->role == "Documents and Records Controller")
        // { 
   
        //     $documents = Document::where('department_id',auth()->user()->department_id)->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray())->get();
        //     $documents_filter = $documents_filter->where('department_id',auth()->user()->department_id)->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray());
        //     $obsoletes = Obsolete::where('department_id',auth()->user()->department_id)->get();
        //     $departments = $departments->where('id',auth()->user()->department_id);
                   
        // }
        
        // if((auth()->user()->role == "Department Head"))
        // {
        //     $documents = Document::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray())->get();
        //     $documents_filter = $documents_filter->whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray());
        //     $obsoletes = Obsolete::whereIn('department_id',(auth()->user()->department_head)->pluck('id')->toArray())->get();
        //     $departments = $departments->whereIn('id',(auth()->user()->department_head)->pluck('id')->toArray());
           
          
        // }
        // if((auth()->user()->role == "User"))
        // {
        //     $documents = Document::where('department_id',auth()->user()->department_id)->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray())->get();
        //     $documents_filter = $documents_filter->where('department_id',auth()->user()->department_id)->orWhereIn('department_id',(auth()->user()->departments)->pluck('department_id')->toArray());
        //     $obsoletes = Obsolete::where('department_id',auth()->user()->department_id)->get();
        //     $departments = $departments->where('id',auth()->user()->department_id);
       
        // }

        // $documents_na = $documents_filter->orderBy('control_code', 'asc')->get();
            // ->paginate(10);
        
 
        return view('documents.documents',
        array(
            'documents' => $documents,
            // 'documents_na' => $documents_na,
            'obsoletes' => $obsoletes,
            // 'departments' => $departments,
            // 'companies' => $companies,
            'document_types' => $document_types,
            'search' => $search,
            'dep' => $department,
            'document_folders' => $document_folders
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function create($id = null)
    {
        $approvers = User::all(); // populate approvers list
        $document_types = DocumentType::get();

        $change_request = null;
        if ($id)
        {
            $change_request = ChangeRequest::with('supporting_documents','approvers.user')->findOrFail($id);
        }
        return view('documents.create', compact('approvers','document_types','change_request'));
    }

    public function signature($id) {
        
        $change_request = ChangeRequest::findOrFail($id);

        return view('documents.signature-assignment',
            array(
                'change_request' => $change_request
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
        //
        $document = Document::findOrfail($id);
        // dd($document);

        return view('view_document',
        array(
            'document' => $document,
            ));
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
    public function showPDF($id)
    {
        ini_set('memory_limit', '-1');
        $attachment = DocumentAttachment::with('document')->findOrFail($id);
            $pdf = new \setasign\Fpdi\Fpdi();
            $newFile = str_replace(' ', '%20', $attachment->attachment);
          
                $fileContentData = file_get_contents(url($newFile));
                try {
                    
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
                            if($pageNo == 1)
                            {
                                $pdf->Write(1, "Effective Date: ".date("m/d/Y",strtotime($attachment->document->updated_at))); 
                            }
                           
                            $pdf->Image('images/uncontrolled.png', 15, 100, 200, '', '', '', '', false, 300);
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
        $folder = new DocumentFolder;
        $folder->name = $request->name;
        $folder->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }
    public function folderView(Request $request,$id)
    {
        $folder = DocumentFolder::with('document')->findOrFail($id);

        return view('documents.folder_view',
            array(
                'folder' => $folder
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
    public function deleteFolder(Request $request,$id)
    {
        $folder = DocumentFolder::with('document')->findOrFail($id);
        if (count($folder->document) > 0)
        {
            Alert::warning('Cannot delete folder because it contains files')->persistent('Dismiss');
            return back();
        }
        else 
        {
            $folder->delete();

            Alert::success('Successfully Deleted')->persistent('Dismiss');
            return back();
        }
    }
}
