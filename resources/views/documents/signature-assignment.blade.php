@extends('layouts.header')

@section('css')
<style>
  .signature-container {
    height: 100vh;
    overflow: hidden;
  }

  #left-panel {
    height: 100vh;
    overflow-y: auto;
    border-right: 1px solid #dee2e6;
  }

  #sigPadWrapper {
    position: relative;
    width: 100%;
    height: 120px;
  }

  #sigPadWrapper::after {
    content: "+";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 28px;
    color: rgba(150,150,150,0.3);
    pointer-events: none;
  }

  #sigPadWrapper::before {
    content: "";
    position: absolute;
    bottom: 30px;
    left: 20px;
    right: 20px;
    height: 2px;
    background: rgba(150,150,150,0.4);
    pointer-events: none;
  }

  #sigPadWrapper .sign-text {
    position: absolute;
    bottom: 8px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 12px;
    color: rgba(150,150,150,0.6);
    pointer-events: none;
    z-index: 1;
  }

  #right-panel {
    height: 100vh;
    display: flex;
    flex-direction: column;
  }

  #pdf-container {
    flex: 1;
    overflow-y: scroll;
    overflow-x: hidden;
    background: #f8f9fa;
    position: relative;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    min-height: 0;
    max-height: calc(100vh - 120px);
  }

  canvas.pdf-page {
    display: block;
    margin: 10px auto;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
  }

  .signature-box {
    position: absolute;
    border: 2px dashed #0d6efd;
    background: rgba(13,110,253,0.1);
    width: 180px;
    height: 80px;
    cursor: move;
    user-select: none;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .signature-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    pointer-events: none;
  }

  .signature-box .box-number {
    font-size: 24px;
    color: #0d6efd;
    font-weight: bold;
  }

  .remove-btn {
    position: absolute;
    top: -10px;
    right: -10px;
    width: 24px;
    height: 24px;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    font-size: 16px;
    font-weight: bold;
    line-height: 22px;
    text-align: center;
    cursor: pointer;
    border: 2px solid white;
  }

  #sigPad {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    width: 100%;
    display: block;
  }
</style>
@endsection

@section('content')
<div class="container-fluid p-0 signature-container">
  <div class="row g-0 h-100">
    <div class="col-md-4 col-lg-3" id="left-panel">
      <div class="p-4">
        <h4 class="mb-3">Signature Setup</h4>

        <div class="card mb-3">
          <div class="card-body">
            <h6 class="card-title fw-bold mb-3">Signature Type</h6>
            
            <div class="btn-group w-100 mb-3" role="group">
              <input type="radio" class="btn-check" name="sigType" id="sigTypeText" value="text" checked>
              <label class="btn btn-outline-primary btn-sm" for="sigTypeText">Text</label>
              
              <input type="radio" class="btn-check" name="sigType" id="sigTypeApprove" value="approve">
              <label class="btn btn-outline-primary btn-sm" for="sigTypeApprove">Approved</label>
              
              <input type="radio" class="btn-check" name="sigType" id="sigTypeHandwritten" value="handwritten">
              <label class="btn btn-outline-primary btn-sm" for="sigTypeHandwritten">Handwritten</label>
            </div>

            <div id="textSignaturePanel">
              <label class="form-label small">Enter your name:</label>
              <input type="text" id="textSignatureInput" class="form-control form-control-sm mb-3" placeholder="Your Name">
              <button id="generateTextSig" class="btn btn-primary btn-sm w-100">Generate Text Signature</button>
            </div>

            <div id="approveSignaturePanel" style="display: none;">
              <div class="text-center mb-3">
                <img id="approveStamp" src="" alt="Approved Stamp" style="max-width: 150px; border: 1px solid #dee2e6; padding: 10px; border-radius: 5px; background: white;">
              </div>
              <button id="useApproveStamp" class="btn btn-primary btn-sm w-100">Use Approved Stamp</button>
            </div>

            <div id="handwrittenSignaturePanel" style="display: none;">
              <div id="sigPadWrapper" class="mb-3">
                <canvas id="sigPad"></canvas>
                <span class="sign-text">Sign here ✎</span>
              </div>
              <div class="d-flex gap-2">
                <button id="clearSig" class="btn btn-outline-secondary btn-sm flex-fill">Clear</button>
                <button id="doneSig" class="btn btn-success btn-sm flex-fill">Done</button>
              </div>
            </div>
          </div>
        </div>

        {{-- <button onclick="placeBox({{ auth()->user()->id }})" class="btn btn-sm btn-outline-primary mb-2 w-100">
          <i class="bi bi-cursor"></i> Place Signature
        </button> --}}

        <button id="savePdf" class="btn btn-primary w-100">
          <i class="bi bi-file-earmark-pdf"></i> Approved PDF
        </button>
      </div>
    </div>

    <div class="col-md-8 col-lg-9" id="right-panel">
      <div class="p-4 pb-2">
        <h4 class="mb-2">PDF Preview</h4>
        <p class="text-muted small mb-3">Select "Place Box" to place signature, then click anywhere on the PDF to drop the signature box.</p>
      </div>
      <div class="px-4 pb-4" style="flex-grow: 1; display: flex; flex-direction: column;">
        {{-- <div id="pdf-container"></div> --}}
        <iframe id="pdf-container"></iframe>
      </div>
    </div>
  </div>
</div>
@endsection

@php
    $file = explode("_", $change_request->file);
    $file_name = $file[1];

    $approver_display = "";
    if ($approver_stamp) 
    {
        $approver_display = $approver_stamp->file;
    }
    else 
    {
        $approver_display = "assets/images/approved.png";
    }
@endphp

@section('js')
<script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pdf-lib/dist/pdf-lib.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  let pdfDoc = null;
  let scale = 1.2;
  let placingSigner = null;
  let currentSignatureData = null;
  let currentSignatureType = 'text';
  let signaturePosition = []
  
  const pdfUrl = '{{ url($change_request->file) }}';
  // const approveStampUrl = "{{asset('assets/images/approved.png')}}";
  const approveStampUrl = "{{asset($approver_display)}}";

  const pdfContainer = document.getElementById("pdf-container");
  const sigCanvas = document.getElementById("sigPad");
  let sigCtx = null;

  document.addEventListener('DOMContentLoaded', function() {
    initializeCanvas();
    loadPdf();
    setupEventListeners();
  });

  function initializeCanvas() {
    const sigPadWrapper = document.getElementById("sigPadWrapper");
    const wrapperWidth = sigPadWrapper.offsetWidth;
    sigCanvas.width = wrapperWidth;
    sigCanvas.height = 120;
    sigCtx = sigCanvas.getContext("2d");
  }

  function setupEventListeners() {
    document.getElementById("approveStamp").src = approveStampUrl;

    document.querySelectorAll('input[name="sigType"]').forEach(radio => {
      radio.addEventListener('change', (e) => {
        currentSignatureType = e.target.value;
        
        document.getElementById('textSignaturePanel').style.display = 'none';
        document.getElementById('approveSignaturePanel').style.display = 'none';
        document.getElementById('handwrittenSignaturePanel').style.display = 'none';
        
        if (currentSignatureType === 'text') {
          document.getElementById('textSignaturePanel').style.display = 'block';
        } else if (currentSignatureType === 'approve') {
          document.getElementById('approveSignaturePanel').style.display = 'block';
        } else if (currentSignatureType === 'handwritten') {
          document.getElementById('handwrittenSignaturePanel').style.display = 'block';
          setTimeout(() => initializeCanvas(), 100);
        }
      });
    });

    // Signature drawing for handwritten
    let drawing = false;
    
    function getMousePos(canvas, evt) {
      const rect = canvas.getBoundingClientRect();
      return {
        x: evt.clientX - rect.left,
        y: evt.clientY - rect.top
      };
    }
    
    sigCanvas.addEventListener("mousedown", (e) => {
      if (currentSignatureType !== 'handwritten') return;
      drawing = true;
      const pos = getMousePos(sigCanvas, e);
      sigCtx.beginPath();
      sigCtx.moveTo(pos.x, pos.y);
    });
    
    sigCanvas.addEventListener("mouseup", () => { 
      drawing = false; 
    });
    
    sigCanvas.addEventListener("mouseleave", () => {
      drawing = false;
    });
    
    sigCanvas.addEventListener("mousemove", (e) => {
      if (!drawing || currentSignatureType !== 'handwritten') return;
      const pos = getMousePos(sigCanvas, e);
      sigCtx.lineWidth = 2;
      sigCtx.lineCap = "round";
      sigCtx.strokeStyle = "black";
      sigCtx.lineTo(pos.x, pos.y);
      sigCtx.stroke();
      sigCtx.beginPath();
      sigCtx.moveTo(pos.x, pos.y);
    });

    // Clear signature
    document.getElementById("clearSig").addEventListener("click", () => {
      sigCtx.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
    });

    document.getElementById("generateTextSig").addEventListener("click", () => {
        $.ajax({
            type:"POST",
            url:"{{ url('documents/signaturePosition') }}",
            data: {
                user_id:"{{ auth()->user()->id }}",
                change_request_id: "{{ $change_request->id }}",
                _token:"{{ csrf_token() }}"
            },
            success: async function(res) {
                const signature = res
                const text = document.getElementById("textSignatureInput").value.trim();
                
                const canvas = document.createElement('canvas');
                canvas.width = 300;
                canvas.height = 120;
                const ctx = canvas.getContext('2d');
            
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.font = '32px "Brush Script MT", cursive';
                ctx.fillStyle = 'black';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(text, canvas.width / 2, canvas.height / 2);
                
                currentSignatureData = canvas.toDataURL('image/png');

                const response = await fetch(pdfUrl);
                const pdfBytes = await response.arrayBuffer();
                const pdfLibDoc = await PDFLib.PDFDocument.load(pdfBytes);
                const pngImage = await pdfLibDoc.embedPng(currentSignatureData);
                const font = await pdfLibDoc.embedFont(PDFLib.StandardFonts.Helvetica)

                signature.forEach((element) => {
                    // const canvases = document.querySelectorAll(".pdf-page");
                    // let pageOffsetTop = 0;
                    let pageIndex = Number(element.page_number) - 1;
                    const page = pdfLibDoc.getPage(pageIndex);
                    const { width, height } = page.getSize();
    
                    const pdfPageHeight = height
                    const htmlY = Number(element.y_position)
                    const yInsidePage = htmlY % pdfPageHeight;
                    const correctedY = pdfPageHeight - yInsidePage - Number(element.height);
                    
                    page.drawImage(pngImage, {
                        x: Number(element.x_position),
                        y: correctedY,
                        width:  Number(element.width),
                        height: Number(element.height)
                    });

                    const date = new Date()
                    const dateToday = date.toLocaleDateString('en-US')
                    const printedName = element.user.name+" - "+dateToday; // expect element.name in DB

                    const fontSize = 10;
                    const nameY = correctedY - 2;

                    page.drawText(printedName, {
                        x: Number(element.x_position) + 45,
                        y: nameY,
                        size: fontSize,
                        font: font,
                        color: PDFLib.rgb(0, 0, 0)
                    });

                    signaturePosition.push(element)
                })

                const signedPdf = await pdfLibDoc.save();
                const blob = new Blob([signedPdf], { type: "application/pdf" });
                const url = URL.createObjectURL(blob);
                document.getElementById('pdf-container').src = url
            }
        })
    
        //   if (!text) {
        //     return Swal.fire({
        //       icon: 'warning',
        //       title: 'No Text',
        //       text: 'Please enter your name first.',
        //       confirmButtonColor: '#0d6efd'
        //     });
        //   }

    //   const boxes = document.querySelectorAll('.signature-box');
    //   boxes.forEach(box => {
    //     updateSignatureBoxDisplay(box, currentSignatureData);
    //   });

        // Swal.fire({
        //     icon: 'success',
        //     title: 'Success!',
        //     text: 'Text signature generated and applied!',
        //     confirmButtonColor: '#0d6efd',
        //     timer: 2000
        // });
    });

    // Use Approve Stamp
    document.getElementById("useApproveStamp").addEventListener("click", () => {

        $.ajax({
            type:"POST",
            url:"{{ url('documents/signaturePosition') }}",
            data: {
                user_id:"{{ auth()->user()->id }}",
                change_request_id: "{{ $change_request->id }}",
                _token:"{{ csrf_token() }}"
            },
            success: async function(res) {
                var signature = res
                
                const img = document.getElementById("approveStamp");
                
                const canvas = document.createElement('canvas');
                canvas.width = 300;
                canvas.height = 120;
                const ctx = canvas.getContext('2d');
                
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                const aspectRatio = img.naturalWidth / img.naturalHeight;
                let drawWidth = 150;
                let drawHeight = drawWidth / aspectRatio;
                
                if (drawHeight > 100) {
                    drawHeight = 100;
                    drawWidth = drawHeight * aspectRatio;
                }
                
                ctx.drawImage(img, (canvas.width - drawWidth) / 2, (canvas.height - drawHeight) / 2, drawWidth, drawHeight);
                
                currentSignatureData = canvas.toDataURL('image/png');
                
                const response = await fetch(pdfUrl);
                const pdfBytes = await response.arrayBuffer();
                const pdfLibDoc = await PDFLib.PDFDocument.load(pdfBytes);
                const pngImage = await pdfLibDoc.embedPng(currentSignatureData);
                const font = await pdfLibDoc.embedFont(PDFLib.StandardFonts.Helvetica)

                signature.forEach(async (element) => {
                    // const canvases = document.querySelectorAll(".pdf-page");
                    // let pageOffsetTop = 0;
                    let pageIndex = Number(element.page_number) - 1;
                    const page = pdfLibDoc.getPage(pageIndex);
                    const { width, height } = page.getSize();
    
                    const pdfPageHeight = height
                    const htmlY = Number(element.y_position)
                    const yInsidePage = htmlY % pdfPageHeight;
                    const correctedY = pdfPageHeight - yInsidePage - Number(element.height);
                    
                    page.drawImage(pngImage, {
                        x: Number(element.x_position),
                        y: correctedY,
                        width:  Number(element.width),
                        height: Number(element.height)
                    });

                    const date = new Date()
                    const dateToday = date.toLocaleDateString('en-US')
                    const printedName = element.user.name+ " - " + dateToday; // expect element.name in DB

                    const fontSize = 10;
                    const nameY = correctedY - 2;

                    page.drawText(printedName, {
                        x: Number(element.x_position) + 45,
                        y: nameY,
                        size: fontSize,
                        font: font,
                        color: PDFLib.rgb(0, 0, 0)
                    });

                    signaturePosition.push(element)
                })

                const signedPdf = await pdfLibDoc.save();
                const blob = new Blob([signedPdf], { type: "application/pdf" });
                const url = URL.createObjectURL(blob);
                document.getElementById('pdf-container').src = url
            }
        })
        
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Approved stamp applied!',
            confirmButtonColor: '#0d6efd',
            timer: 2000
        });
    });

    document.getElementById("doneSig").addEventListener("click", () => {
        $.ajax({
            type:"POST",
            url:"{{ url('documents/signaturePosition') }}",
            data: {
                user_id:"{{ auth()->user()->id }}",
                change_request_id: "{{ $change_request->id }}",
                _token:"{{ csrf_token() }}"
            },
            success: async function(res) {
                // const img = document.getElementById("doneSig");
                
                // const canvas = document.createElement('canvas');
                // canvas.width = 300;
                // canvas.height = 120;
                // const ctx = canvas.getContext('2d');
                
                // ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                // const aspectRatio = img.naturalWidth / img.naturalHeight;
                // let drawWidth = 150;
                // let drawHeight = drawWidth / aspectRatio;
                
                // if (drawHeight > 100) {
                //     drawHeight = 100;
                //     drawWidth = drawHeight * aspectRatio;
                // }
                
                // ctx.drawImage(img, (canvas.width - drawWidth) / 2, (canvas.height - drawHeight) / 2, drawWidth, drawHeight);
                // const canvases = document.querySelectorAll(".pdf-page");

                const signature = res 

                const sigData = sigCanvas.toDataURL("image/png");
                currentSignatureData = sigData;
                
                const response = await fetch(pdfUrl);
                const pdfBytes = await response.arrayBuffer();
                const pdfLibDoc = await PDFLib.PDFDocument.load(pdfBytes);
                const pngImage = await pdfLibDoc.embedPng(currentSignatureData);
                const font = await pdfLibDoc.embedFont(PDFLib.StandardFonts.Helvetica)

                signature.forEach(element => {
                    
                    let pageIndex = Number(element.page_number) - 1;
                    let pageOffsetTop = 0;
    
                    const page = pdfLibDoc.getPage(pageIndex);
                    const { width, height } = page.getSize();
    
                    // const correctedY = height - Number(element.y_position) - Number(element.height)
                    const pdfPageHeight = height
                    const htmlY = Number(element.y_position)
                    const yInsidePage = htmlY % pdfPageHeight;
                    const correctedY = pdfPageHeight - yInsidePage - Number(element.height);
    
                    page.drawImage(pngImage, {
                        x: Number(element.x_position),
                        y: correctedY,
                        width:  Number(element.width),
                        height: Number(element.height)
                    });

                    const date = new Date()
                    const dateToday = date.toLocaleDateString('en-US')
                    const printedName = element.user.name+" - "+dateToday; // expect element.name in DB

                    const fontSize = 10;
                    const nameY = correctedY - 2;

                    page.drawText(printedName, {
                        x: Number(element.x_position) + 45,
                        y: nameY,
                        size: fontSize,
                        font: font,
                        color: PDFLib.rgb(0, 0, 0)
                    });

                    signaturePosition.push(element)
                })

                const signedPdf = await pdfLibDoc.save();
                const blob = new Blob([signedPdf], { type: "application/pdf" });
                const url = URL.createObjectURL(blob);
                
                document.getElementById('pdf-container').src = url
            }
        })
    
        //   if (sigData === emptyData) {
        //     return Swal.fire({
        //       icon: 'warning',
        //       title: 'No Signature',
        //       text: 'Please draw a signature first.',
        //       confirmButtonColor: '#0d6efd'
        //      });
        //   }

        //   const boxes = document.querySelectorAll('.signature-box');
        //   boxes.forEach(box => {
        //     updateSignatureBoxDisplay(box, sigData);
        //   });

        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Signature updated in all boxes!',
            confirmButtonColor: '#0d6efd',
            timer: 2000
        });
    });

    // Save PDF
    document.getElementById("savePdf").addEventListener("click", async () => {
        try {
            // if (!pdfDoc) {
            //     return Swal.fire({
            //         icon: 'error',
            //         title: 'PDF Not Loaded',
            //         text: 'PDF document is not loaded yet.',
            //         confirmButtonColor: '#0d6efd'
            //     });
            // }
            
            // if (!currentSignatureData) {
            //     return Swal.fire({
            //         icon: 'warning',
            //         title: 'No Signature',
            //         text: 'Please generate a signature first.',
            //         confirmButtonColor: '#0d6efd'
            //     });
            // }

            // Swal.fire({
            // title: 'Generating PDF...',
            // text: 'Please wait while we prepare your signed document.',
            // allowOutsideClick: false,
            // didOpen: () => {
            //     Swal.showLoading();
            // }
            // });

            const response = await fetch(pdfUrl);
            const pdfBytes = await response.arrayBuffer();
            const pdfLibDoc = await PDFLib.PDFDocument.load(pdfBytes);
            const pngImage = await pdfLibDoc.embedPng(currentSignatureData);
            const font = await pdfLibDoc.embedFont(PDFLib.StandardFonts.Helvetica)

            // const box = document.querySelector('.signature-box')
            
            // const canvases = document.querySelectorAll(".pdf-page");
            // let pageIndex = 0;
            // let pageOffsetTop = 0;

            signaturePosition.forEach(element => {
                const signaturePositionArray = element
                const pageIndex = Number(signaturePositionArray.page_number) - 1
    
                const page = pdfLibDoc.getPage(pageIndex);
                const { width, height } = page.getSize();
    
                // const correctedY = height - Number(signaturePositionArray.y_position) - Number(signaturePositionArray.height)
                const pdfPageHeight = height
                const htmlY = Number(element.y_position)
                const yInsidePage = htmlY % pdfPageHeight;
                const correctedY = pdfPageHeight - yInsidePage - Number(element.height);
    
                page.drawImage(pngImage, {
                    x: Number(signaturePositionArray.x_position),
                    y: correctedY,
                    width:  Number(signaturePositionArray.width),
                    height: Number(signaturePositionArray.height)
                });

                const date = new Date()
                const dateToday = date.toLocaleDateString('en-US')

                const printedName = element.user.name+" - "+dateToday; // expect element.name in DB
                const fontSize = 10;
                const nameY = correctedY - 2;

                page.drawText(printedName, {
                    x: Number(element.x_position) + 45,
                    y: nameY,
                    size: fontSize,
                    font: font,
                    color: PDFLib.rgb(0, 0, 0)
                });
            })
            
            // const boxTop = parseFloat(box.style.top);
            
            // for (let i = 0; i < canvases.length; i++) {
            //     const canvasTop = canvases[i].offsetTop;
            //     const canvasBottom = canvasTop + canvases[i].height;
                
            //     if (boxTop >= canvasTop && boxTop < canvasBottom) {
            //         pageIndex = i;
            //         pageOffsetTop = canvasTop;
            //         break;
            //     }
            // }

            // const page = pdfLibDoc.getPage(pageIndex);
            // const { height } = page.getSize();

            // const htmlLeft = parseFloat(box.style.left);
            // const htmlTop = parseFloat(box.style.top) - pageOffsetTop;

            // const canvas = canvases[pageIndex];
            // const canvasLeft = canvas.offsetLeft;

            // const x = (htmlLeft - canvasLeft) / scale;
            // const y = height - (htmlTop / scale) - (80 / scale);

            // page.drawImage(pngImage, {
            //     x,
            //     y,
            //     width: 180 / scale,
            //     height: 80 / scale
            // });

            const signedPdf = await pdfLibDoc.save({useObjectStreams:false, compress:false});
            // const blob = new Blob([signedPdf], { type: "application/pdf" });
            // const url = URL.createObjectURL(blob);
            
            // const link = document.createElement("a");
            // link.href = url;
            // link.download = "signed_document.pdf";
            // link.click();
            
            const filename = "<?php echo($file_name) ?>"
            const changeRequestId = "<?php echo($change_request->id) ?>"

            const formData = new FormData()
            formData.append("old_status", "Pending")
            formData.append("action", "Approved")
            formData.append("file", new Blob([signedPdf],{type:"application/pdf"}), filename)

            $.ajax({
                type:"POST",
                url:"{{ url('change-request/change-request-action') }}/" + changeRequestId,
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                beforeSend: function(){
                    show()
                },
                success: function(response) {
                    if (response.status == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            confirmButtonColor: '#0d6efd'
                        });

                        setTimeout(() => {
                            window.location.href = "{{ url('for-approval') }}"
                        }, 1000)
                    }
                }
            })
            
        } catch (error) {
            console.error("Error generating PDF:", error);
            Swal.fire({
            icon: 'error',
            title: 'Error Generating PDF',
            text: error.message,
            confirmButtonColor: '#0d6efd'
            });
        }
    });
  }

//   function updateSignatureBoxDisplay(box, sigData) {
//     const oldImg = box.querySelector('img');
//     const oldNumber = box.querySelector('.box-number');
//     if (oldImg) oldImg.remove();
//     if (oldNumber) oldNumber.remove();

//     const img = document.createElement('img');
//     img.src = sigData;
//     box.appendChild(img);
//   }

  async function loadPdf() {
    try {
    //   const response = await fetch(pdfUrl);
    //   const arrayBuffer = await response.arrayBuffer();
    //   const loadingTask = pdfjsLib.getDocument({ data: arrayBuffer });
    //   pdfDoc = await loadingTask.promise;

    //   pdfContainer.innerHTML = "";
    //   for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
    //     const page = await pdfDoc.getPage(pageNum);
    //     const viewport = page.getViewport({ scale });
    //     const canvas = document.createElement("canvas");
    //     canvas.className = "pdf-page";
    //     const context = canvas.getContext("2d");
    //     canvas.height = viewport.height;
    //     canvas.width = viewport.width;
    //     await page.render({ canvasContext: context, viewport }).promise;
    //     pdfContainer.appendChild(canvas);
    //   }

    const iframe = document.getElementById("pdf-container")
    iframe.src = pdfUrl

    //   pdfContainer.onclick = (e) => {
    //     if (placingSigner === null) return;
    //     addSignatureBox(e, placingSigner);
    //     placingSigner = null;
    //   };
    } catch (error) {
      console.error("Error loading PDF:", error);
      Swal.fire({
        icon: 'error',
        title: 'Error Loading PDF',
        text: error.message,
        confirmButtonColor: '#0d6efd'
      });
    }
  }

//   window.placeBox = (index) => {
//     placingSigner = index;
//     Swal.fire({
//       icon: 'info',
//       title: 'Place Signature Box',
//       text: `Click on the PDF to place signature box`,
//       confirmButtonColor: '#0d6efd',
//       timer: 3000
//     });
//   };

//   function addSignatureBox(e, index) {
//     const rect = pdfContainer.getBoundingClientRect();
//     const x = e.clientX - rect.left + pdfContainer.scrollLeft;
//     const y = e.clientY - rect.top + pdfContainer.scrollTop;

//     const sigBox = document.createElement("div");
//     sigBox.classList.add("signature-box");
//     sigBox.style.left = x - 90 + "px";
//     sigBox.style.top = y - 40 + "px";

//     if (currentSignatureData) {
//       const img = document.createElement('img');
//       img.src = currentSignatureData;
//       sigBox.appendChild(img);
//     } else {
//       const number = document.createElement('span');
//       number.className = 'box-number';
//       sigBox.appendChild(number);
//     }

//     const removeBtn = document.createElement("div");
//     removeBtn.classList.add("remove-btn");
//     removeBtn.textContent = "×";
//     removeBtn.onclick = (ev) => {
//       ev.stopPropagation();
//       pdfContainer.removeChild(sigBox);
//     };
//     sigBox.appendChild(removeBtn);

//     makeDraggable(sigBox);
//     pdfContainer.appendChild(sigBox);
//   }

//   function makeDraggable(el) {
//     let offsetX, offsetY;
//     el.addEventListener("mousedown", (e) => {
//       if (e.target.classList.contains("remove-btn")) return;
      
//       const rect = pdfContainer.getBoundingClientRect();
//       const boxRect = el.getBoundingClientRect();
      
//       offsetX = e.clientX - boxRect.left;
//       offsetY = e.clientY - boxRect.top;
      
//       document.onmousemove = (moveEvent) => {
//         const newLeft = moveEvent.clientX - rect.left + pdfContainer.scrollLeft - offsetX;
//         const newTop = moveEvent.clientY - rect.top + pdfContainer.scrollTop - offsetY;
        
//         el.style.left = newLeft + "px";
//         el.style.top = newTop + "px";
//       };
//       document.onmouseup = () => {
//         document.onmousemove = null;
//         document.onmouseup = null;
//       };
//     });
//   }
</script>
@endsection