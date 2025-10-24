{{-- resources/views/pdf/fabric-editor.blade.php --}}
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Fabric PDF Editor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  @vite('resources/js/fabric-editor.js')
  <style>
    html, body { height: 100%; margin: 0; }
    .toolbar { padding: 8px; border-bottom: 1px solid #ddd; display:flex; gap:8px; flex-wrap:wrap; }
    #pdf-root { height: calc(100% - 52px); overflow: auto; }
    .page { position: relative; margin: 16px auto; width: fit-content; }
    .canvas-layer { display:block; }
    .fabric-layer { position:absolute; left:0; top:0; }
  </style>
</head>
<body>
  <div class="toolbar">
    <input type="file" id="pdfFile" accept="application/pdf" />
    <button id="openFile">Open</button>
    <button id="addText">Text</button>
    <button id="addRect">Rect</button>
    <button id="addCircle">Circle</button>
    <input type="file" id="imageFile" accept="image/*" style="display:none" />
    <button id="addImage">Image</button>
    <button id="zoomIn">+</button>
    <button id="zoomOut">-</button>
    <button id="exportPdf">Export PDF</button>
  </div>
  <div id="app" data-initial-file="{{ $file }}">
    <div id="pdf-root"></div>
  </div>
</body>
</html>

