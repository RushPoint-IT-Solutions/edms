<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Viewer with QR Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #525659;
        }

        .pdf-toolbar {
            background: #323639;
            color: white;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .pdf-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .pdf-toolbar button {
            background: #454a4d;
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            transition: background 0.2s;
        }

        .pdf-toolbar button:hover {
            background: #5a6063;
        }

        .pdf-toolbar button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .page-info input {
            width: 50px;
            padding: 5px;
            text-align: center;
            background: #454a4d;
            border: 1px solid #5a6063;
            color: white;
            border-radius: 4px;
        }

        .pdf-container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .pdf-page-wrapper {
            position: relative;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            display: inline-block;
        }

        .pdf-canvas {
            display: block;
            width: 100%;
            height: auto;
        }

        .qr-overlay {
            position: absolute;
            bottom: 10px;
            right: 10px;
            padding: 5px;
            border-radius: 8px;
            z-index: 10;
        }

        .qr-overlay img {
            display: block;
            width: 70px;
            height: 70px;
        }

        .zoom-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .zoom-value {
            min-width: 60px;
            text-align: center;
            font-size: 14px;
        }

        .loading {
            text-align: center;
            color: white;
            padding: 50px;
            font-size: 18px;
        }

        .loading i {
            font-size: 48px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @media print {
            body {
                background: white;
            }
            .pdf-toolbar {
                display: none !important;
            }
            .pdf-container {
                max-width: none;
                margin: 0;
                padding: 0;
            }
            .pdf-page-wrapper {
                page-break-after: always;
                margin: 0;
                box-shadow: none;
                page-break-inside: avoid;
            }
            .qr-overlay {
                display: block !important;
                position: absolute;
                bottom: 10px;
                right: 10px;
                border-radius: 8px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .qr-overlay img {
                display: block !important;
            }
        }

        @media (max-width: 768px) {
            .pdf-toolbar {
                flex-direction: column;
                gap: 10px;
            }
            .pdf-controls {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>
    <div class="pdf-toolbar">
        <div class="pdf-controls">
            <button onclick="window.close()">
                <i class="ri-close-line"></i> Close
            </button>
            <div class="page-info">
                <button id="prevPage" disabled>
                    <i class="ri-arrow-left-s-line"></i>
                </button>
                <span>
                    <input type="number" id="pageNum" value="1" min="1"> / <span id="pageCount">0</span>
                </span>
                <button id="nextPage" disabled>
                    <i class="ri-arrow-right-s-line"></i>
                </button>
            </div>
        </div>
        <div class="zoom-controls">
            <button id="zoomOut">
                <i class="ri-zoom-out-line"></i>
            </button>
            <span class="zoom-value" id="zoomValue">100%</span>
            <button id="zoomIn">
                <i class="ri-zoom-in-line"></i>
            </button>
            <button id="fitWidth">
                <i class="ri-fullscreen-line"></i> Fit Width
            </button>
        </div>
        <div class="pdf-controls">
            <button onclick="window.print()">
                <i class="ri-printer-line"></i> Print
            </button>
            <button id="downloadPdf">
                <i class="ri-download-line"></i> Download
            </button>
        </div>
    </div>

    <div class="loading" id="loading">
        <i class="ri-loader-4-line"></i>
        <div>Loading PDF...</div>
    </div>

    <div class="pdf-container" id="pdfContainer" style="display: none;"></div>

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const pdfUrl = '../document_attachments/1762761235_LRC_MANUAL.pdf';
        const qrCodeImagePath = "{{asset('assets/images/qr_code.png')}}";
        
        const qrData = {
            docId: 'Doc-2024-001',
            url: window.location.origin + '/document/Doc-2024-001'
        };

        let pdfDoc = null;
        let pageNum = 1;
        let pageCount = 0;
        let scale = 1.0;
        let rendering = false;

        const container = document.getElementById('pdfContainer');
        const loading = document.getElementById('loading');
        const pageNumInput = document.getElementById('pageNum');
        const pageCountSpan = document.getElementById('pageCount');
        const prevBtn = document.getElementById('prevPage');
        const nextBtn = document.getElementById('nextPage');
        const zoomValue = document.getElementById('zoomValue');

        async function loadPDF() {
            try {
                const loadingTask = pdfjsLib.getDocument(pdfUrl);
                pdfDoc = await loadingTask.promise;
                pageCount = pdfDoc.numPages;
                pageCountSpan.textContent = pageCount;
                pageNumInput.max = pageCount;
                
                loading.style.display = 'none';
                container.style.display = 'flex';
                
                renderAllPages();
            } catch (error) {
                loading.innerHTML = '<i class="ri-error-warning-line"></i><div>Error loading PDF</div>';
                console.error('Error loading PDF:', error);
            }
        }

        async function renderAllPages() {
            container.innerHTML = '';
            
            for (let i = 1; i <= pageCount; i++) {
                await renderPage(i);
            }
            
            updateButtons();
        }

        async function renderPage(num) {
            const page = await pdfDoc.getPage(num);
            const viewport = page.getViewport({ scale: scale });

            const wrapper = document.createElement('div');
            wrapper.className = 'pdf-page-wrapper';
            wrapper.id = `page-${num}`;

            const canvas = document.createElement('canvas');
            canvas.className = 'pdf-canvas';
            const context = canvas.getContext('2d');

            canvas.height = viewport.height;
            canvas.width = viewport.width;

            wrapper.appendChild(canvas);

            const qrDiv = document.createElement('div');
            qrDiv.className = 'qr-overlay';
            
            const qrImg = document.createElement('img');
            qrImg.src = qrCodeImagePath;
            qrImg.alt = 'Document QR Code';
            qrImg.onerror = function() {
                this.style.display = 'none';
                console.warn('QR code image not found at:', qrCodeImagePath);
            };
            
            qrDiv.appendChild(qrImg);
            wrapper.appendChild(qrDiv);

            container.appendChild(wrapper);

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            await page.render(renderContext).promise;
        }

        function zoomIn() {
            scale += 0.25;
            if (scale > 3) scale = 3;
            updateZoom();
        }

        function zoomOut() {
            scale -= 0.25;
            if (scale < 0.5) scale = 0.5;
            updateZoom();
        }

        function fitWidth() {
            const containerWidth = container.offsetWidth - 40;
            pdfDoc.getPage(1).then(p => {
                const viewport = p.getViewport({ scale: 1 });
                scale = containerWidth / viewport.width;
                updateZoom();
            });
        }

        function updateZoom() {
            zoomValue.textContent = Math.round(scale * 100) + '%';
            renderAllPages();
        }

        function updateButtons() {
            prevBtn.disabled = pageNum <= 1;
            nextBtn.disabled = pageNum >= pageCount;
        }

        function goToPage(num) {
            if (num >= 1 && num <= pageCount) {
                pageNum = num;
                pageNumInput.value = num;
                const pageElement = document.getElementById(`page-${num}`);
                if (pageElement) {
                    pageElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                updateButtons();
            }
        }

        prevBtn.addEventListener('click', () => goToPage(pageNum - 1));
        nextBtn.addEventListener('click', () => goToPage(pageNum + 1));
        pageNumInput.addEventListener('change', (e) => goToPage(parseInt(e.target.value)));
        document.getElementById('zoomIn').addEventListener('click', zoomIn);
        document.getElementById('zoomOut').addEventListener('click', zoomOut);
        document.getElementById('fitWidth').addEventListener('click', fitWidth);

        document.getElementById('downloadPdf').addEventListener('click', async () => {
            try {
                const existingPdfBytes = await fetch(pdfUrl).then(res => res.arrayBuffer());
                
                const link = document.createElement('a');
                link.href = pdfUrl;
                link.download = 'document_with_qr.pdf';
                link.click();
                
                alert('Note: The downloaded PDF includes the QR code when printed. For permanent QR embedding, server-side processing is recommended.');
            } catch (error) {
                console.error('Download error:', error);
                const link = document.createElement('a');
                link.href = pdfUrl;
                link.download = 'document.pdf';
                link.click();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') goToPage(pageNum - 1);
            if (e.key === 'ArrowRight') goToPage(pageNum + 1);
            if (e.key === '+' || e.key === '=') zoomIn();
            if (e.key === '-') zoomOut();
        });

        window.addEventListener('scroll', () => {
            const pages = document.querySelectorAll('.pdf-page-wrapper');
            const scrollPos = window.scrollY + window.innerHeight / 2;
            
            pages.forEach((page, index) => {
                const rect = page.getBoundingClientRect();
                const pageTop = rect.top + window.scrollY;
                const pageBottom = pageTop + rect.height;
                
                if (scrollPos >= pageTop && scrollPos <= pageBottom) {
                    pageNum = index + 1;
                    pageNumInput.value = pageNum;
                    updateButtons();
                }
            });
        });

        loadPDF();
    </script>
</body>
</html>