<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Viewer with Text</title>
</head>
<style>
    canvas {
        border: 1px solid black;
        box-shadow: 2px 2px 12px rgba(0,0,0,0.5);
        margin: 4px;
        width: 98%;
        height: 98%;
    }
</style>
<body>
    {{-- <div id="pdf-viewer"> --}}
        <div id="pdf-container" style="overflow-y: scroll; height: 100vh;">
      </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script>
        const url = 'assets/Цахимномынсан.pdf';

        const pdfContainer = document.getElementById('pdf-container');

        const renderPage = (page, scale) => {
        const viewport = page.getViewport({ scale });
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        canvas.height = viewport.height;
        canvas.width = viewport.width;

        pdfContainer.appendChild(canvas);

        const renderContext = {
            canvasContext: ctx,
            viewport: viewport
        };
        page.render(renderContext);
        };

        const loadPdf = (pdf) => {
        const scale = 1.5;

        for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
            pdf.getPage(pageNum).then(page => renderPage(page, scale));
        }
        };

        pdfjsLib.getDocument(url).promise.then(pdf => loadPdf(pdf));

        // let pdfDoc = null,
        //     pageNum = 1,
        //     pageIsRendering = false,
        //     pageNumIsPending = null;

        // const scale = 1.5,
        //     canvas = document.getElementById('pdf-canvas'),
        //     ctx = canvas.getContext('2d');

        // // Render the page
        // const renderPage = (num) => {
        //     pageIsRendering = true;

        //     // Get page
        //     pdfDoc.getPage(num).then((page) => {
        //         // Set scale
        //         const viewport = page.getViewport({ scale });
        //         canvas.height = viewport.height;
        //         canvas.width = viewport.width;

        //         const renderCtx = {
        //             canvasContext: ctx,
        //             viewport,
        //         };

        //         page.render(renderCtx).promise.then(() => {
        //             pageIsRendering = false;

        //             if (pageNumIsPending !== null) {
        //                 renderPage(pageNumIsPending);
        //                 pageNumIsPending = null;
        //             }
        //         });

        //         // Output current page
        //         document.getElementById('page-num').textContent = num;
        //     });
        // };

        // // Check for pages rendering
        // const queueRenderPage = (num) => {
        //     if (pageIsRendering) {
        //         pageNumIsPending = num;
        //     } else {
        //         renderPage(num);
        //     }
        // };

        // // Show Prev Page
        // const showPrevPage = () => {
        //     if (pageNum <= 1) {
        //         return;
        //     }
        //     pageNum--;
        //     queueRenderPage(pageNum);
        // };

        // // Show Next Page
        // const showNextPage = () => {
        //     if (pageNum >= pdfDoc.numPages) {
        //         return;
        //     }
        //     pageNum++;
        //     queueRenderPage(pageNum);
        // };

        // // Get Document
        // pdfjsLib.getDocument(url).promise.then((pdfDoc_) => {
        //     pdfDoc = pdfDoc_;

        //     document.getElementById('page-count').textContent = pdfDoc.numPages;

        //     renderPage(pageNum);
        // });

        // // Button Events
        // document.getElementById('prev-page').addEventListener('click', showPrevPage);
        // document.getElementById('next-page').addEventListener('click', showNextPage);
    </script>
</body>
</html>
