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

    <div id="pdf-container" style="overflow-y: scroll; height: 100vh;">
    </div>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
        <script>
            const url = 'assets/Архив4.pdf';

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
    </script> --}}
</body>
</html>
