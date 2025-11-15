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

  #signers-list {
    max-height: 200px;
    overflow-y: auto;
  }

  .signer-item {
    background: #e7f3ff;
    border: 1px solid #0d6efd;
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
            <h6 class="card-title fw-bold mb-3">Add Signatory</h6>
            <div class="mb-2">
              <input type="text" id="signerName" class="form-control form-control-sm" placeholder="Name">
            </div>
            <div class="mb-3">
              <input type="email" id="signerEmail" class="form-control form-control-sm" placeholder="Email">
            </div>
            <button id="addSigner" class="btn btn-primary btn-sm w-100">
              <i class="bi bi-plus-circle"></i> Add Signer
            </button>

            <div id="signers-list" class="mt-3"></div>
          </div>
        </div>

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

        <button id="savePdf" class="btn btn-primary w-100">
          <i class="bi bi-file-earmark-pdf"></i> Generate Signed PDF
        </button>
      </div>
    </div>

    <div class="col-md-8 col-lg-9" id="right-panel">
      <div class="p-4 pb-2">
        <h4 class="mb-2">PDF Preview</h4>
        <p class="text-muted small mb-3">Select "Place Box" for a signer, then click anywhere on the PDF to drop their signature box.</p>
      </div>
      <div class="px-4 pb-4" style="flex-grow: 1; display: flex; flex-direction: column;">
        <div id="pdf-container"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pdf-lib/dist/pdf-lib.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  let pdfDoc = null;
  let scale = 1.2;
  let placingSigner = null;
  let signers = [];
  let currentSignatureData = null;
  let currentSignatureType = 'text';
  
  const pdfUrl = '{{ asset("document_attachments/1722316806_sample.pdf") }}';
  const approveStampUrl = "{{asset('assets/images/approved.png')}}";

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
      const text = document.getElementById("textSignatureInput").value.trim();
      
      if (!text) {
        return Swal.fire({
          icon: 'warning',
          title: 'No Text',
          text: 'Please enter your name first.',
          confirmButtonColor: '#0d6efd'
        });
      }

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
      
      signers.forEach(s => {
        if (s.box) {
          updateSignatureBoxDisplay(s.box, currentSignatureData);
        }
      });

      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Text signature generated and applied!',
        confirmButtonColor: '#0d6efd',
        timer: 2000
      });
    });

    // Use Approve Stamp
    document.getElementById("useApproveStamp").addEventListener("click", () => {
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
      
      signers.forEach(s => {
        if (s.box) {
          updateSignatureBoxDisplay(s.box, currentSignatureData);
        }
      });

      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Approved stamp applied!',
        confirmButtonColor: '#0d6efd',
        timer: 2000
      });
    });

    document.getElementById("doneSig").addEventListener("click", () => {
      const sigData = sigCanvas.toDataURL("image/png");
      
      const tempCanvas = document.createElement('canvas');
      tempCanvas.width = sigCanvas.width;
      tempCanvas.height = sigCanvas.height;
      const emptyData = tempCanvas.toDataURL("image/png");
      
      if (sigData === emptyData) {
        return Swal.fire({
          icon: 'warning',
          title: 'No Signature',
          text: 'Please draw a signature first.',
          confirmButtonColor: '#0d6efd'
        });
      }

      currentSignatureData = sigData;

      signers.forEach(s => {
        if (s.box) {
          updateSignatureBoxDisplay(s.box, sigData);
        }
      });

      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Signature updated in all boxes!',
        confirmButtonColor: '#0d6efd',
        timer: 2000
      });
    });

    // Add signers
    document.getElementById("addSigner").addEventListener("click", () => {
      const name = document.getElementById("signerName").value.trim();
      const email = document.getElementById("signerEmail").value.trim();
      if (!name || !email) {
        return Swal.fire({
          icon: 'warning',
          title: 'Missing Information',
          text: 'Enter both name and email.',
          confirmButtonColor: '#0d6efd'
        });
      }

      const order = signers.length + 1;
      signers.push({ name, email, order, box: null });
      renderSigners();

      document.getElementById("signerName").value = "";
      document.getElementById("signerEmail").value = "";
      
      Swal.fire({
        icon: 'success',
        title: 'Signer Added!',
        text: `${name} has been added to the list.`,
        confirmButtonColor: '#0d6efd',
        timer: 1500,
        showConfirmButton: false
      });
    });

    // Save PDF
    document.getElementById("savePdf").addEventListener("click", async () => {
      try {
        if (!pdfDoc) {
          return Swal.fire({
            icon: 'error',
            title: 'PDF Not Loaded',
            text: 'PDF document is not loaded yet.',
            confirmButtonColor: '#0d6efd'
          });
        }
        
        if (!currentSignatureData) {
          return Swal.fire({
            icon: 'warning',
            title: 'No Signature',
            text: 'Please generate a signature first.',
            confirmButtonColor: '#0d6efd'
          });
        }

        Swal.fire({
          title: 'Generating PDF...',
          text: 'Please wait while we prepare your signed document.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        const response = await fetch(pdfUrl);
        const pdfBytes = await response.arrayBuffer();
        const pdfLibDoc = await PDFLib.PDFDocument.load(pdfBytes);
        const pngImage = await pdfLibDoc.embedPng(currentSignatureData);

        signers.forEach(s => {
          if (!s.box) return;
          
          const canvases = document.querySelectorAll(".pdf-page");
          let pageIndex = 0;
          let pageOffsetTop = 0;
          
          const boxTop = parseFloat(s.box.style.top);
          
          for (let i = 0; i < canvases.length; i++) {
            const canvasTop = canvases[i].offsetTop;
            const canvasBottom = canvasTop + canvases[i].height;
            
            if (boxTop >= canvasTop && boxTop < canvasBottom) {
              pageIndex = i;
              pageOffsetTop = canvasTop;
              break;
            }
          }

          const page = pdfLibDoc.getPage(pageIndex);
          const { height } = page.getSize();

          const htmlLeft = parseFloat(s.box.style.left);
          const htmlTop = parseFloat(s.box.style.top) - pageOffsetTop;

          const canvas = canvases[pageIndex];
          const canvasLeft = canvas.offsetLeft;

          const x = (htmlLeft - canvasLeft) / scale;
          const y = height - (htmlTop / scale) - (80 / scale);

          page.drawImage(pngImage, {
            x,
            y,
            width: 180 / scale,
            height: 80 / scale
          });
        });

        const signedPdf = await pdfLibDoc.save();
        const blob = new Blob([signedPdf], { type: "application/pdf" });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "signed.pdf";
        link.click();

        Swal.fire({
          icon: 'success',
          title: 'PDF Generated!',
          text: 'Your signed PDF has been downloaded successfully.',
          confirmButtonColor: '#0d6efd'
        });
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

  function updateSignatureBoxDisplay(box, sigData) {
    const oldImg = box.querySelector('img');
    const oldNumber = box.querySelector('.box-number');
    if (oldImg) oldImg.remove();
    if (oldNumber) oldNumber.remove();

    const img = document.createElement('img');
    img.src = sigData;
    box.appendChild(img);
  }

  async function loadPdf() {
    try {
      const response = await fetch(pdfUrl);
      const arrayBuffer = await response.arrayBuffer();
      const loadingTask = pdfjsLib.getDocument({ data: arrayBuffer });
      pdfDoc = await loadingTask.promise;

      pdfContainer.innerHTML = "";
      for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
        const page = await pdfDoc.getPage(pageNum);
        const viewport = page.getViewport({ scale });
        const canvas = document.createElement("canvas");
        canvas.className = "pdf-page";
        const context = canvas.getContext("2d");
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        await page.render({ canvasContext: context, viewport }).promise;
        pdfContainer.appendChild(canvas);
      }

      pdfContainer.onclick = (e) => {
        if (placingSigner === null) return;
        addSignatureBox(e, placingSigner);
        placingSigner = null;
      };
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

  function renderSigners() {
    const list = document.getElementById("signers-list");
    list.innerHTML = "";
    
    if (signers.length === 0) return;
    
    list.innerHTML = '<div class="border-top pt-2 mt-2"></div>';
    
    signers.forEach((s, i) => {
      const div = document.createElement("div");
      div.className = "signer-item rounded p-2 mb-2";
      div.innerHTML = `
        <div class="fw-bold small">${s.order}. ${s.name}</div>
        <div class="small text-muted">${s.email}</div>
        <button onclick="placeBox(${i})" class="btn btn-sm btn-outline-primary mt-2 w-100">
          <i class="bi bi-cursor"></i> Place Box
        </button>
      `;
      list.appendChild(div);
    });
  }

  window.placeBox = (index) => {
    placingSigner = index;
    Swal.fire({
      icon: 'info',
      title: 'Place Signature Box',
      text: `Click on the PDF to place signature box for: ${signers[index].name}`,
      confirmButtonColor: '#0d6efd',
      timer: 3000
    });
  };

  function addSignatureBox(e, index) {
    const rect = pdfContainer.getBoundingClientRect();
    const x = e.clientX - rect.left + pdfContainer.scrollLeft;
    const y = e.clientY - rect.top + pdfContainer.scrollTop;

    if (signers[index].box) pdfContainer.removeChild(signers[index].box);

    const sigBox = document.createElement("div");
    sigBox.classList.add("signature-box");
    sigBox.style.left = x - 90 + "px";
    sigBox.style.top = y - 40 + "px";

    if (currentSignatureData) {
      const img = document.createElement('img');
      img.src = currentSignatureData;
      sigBox.appendChild(img);
    } else {
      const number = document.createElement('span');
      number.className = 'box-number';
      number.textContent = signers[index].order;
      sigBox.appendChild(number);
    }

    const removeBtn = document.createElement("div");
    removeBtn.classList.add("remove-btn");
    removeBtn.textContent = "×";
    removeBtn.onclick = (ev) => {
      ev.stopPropagation();
      pdfContainer.removeChild(sigBox);
      signers[index].box = null;
    };
    sigBox.appendChild(removeBtn);

    makeDraggable(sigBox);
    pdfContainer.appendChild(sigBox);

    signers[index].box = sigBox;
  }

  function makeDraggable(el) {
    let offsetX, offsetY;
    el.addEventListener("mousedown", (e) => {
      if (e.target.classList.contains("remove-btn")) return;
      
      const rect = pdfContainer.getBoundingClientRect();
      const boxRect = el.getBoundingClientRect();
      
      offsetX = e.clientX - boxRect.left;
      offsetY = e.clientY - boxRect.top;
      
      document.onmousemove = (moveEvent) => {
        const newLeft = moveEvent.clientX - rect.left + pdfContainer.scrollLeft - offsetX;
        const newTop = moveEvent.clientY - rect.top + pdfContainer.scrollTop - offsetY;
        
        el.style.left = newLeft + "px";
        el.style.top = newTop + "px";
      };
      document.onmouseup = () => {
        document.onmousemove = null;
        document.onmouseup = null;
      };
    });
  }
</script>
@endsection