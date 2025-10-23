{{-- resources/views/pdf/editor.blade.php --}}
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>PDF Editor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    html, body, #viewer { height: 100%; margin: 0; }
    .toolbar { padding: 8px; border-bottom: 1px solid #ddd; }
  </style>
</head>
<body>
  <div class="toolbar">
    <form id="uploadForm" style="display:inline-block">
      <input type="file" id="pdfFile" accept="application/pdf" />
      <button type="submit">Upload & Open</button>
    </form>
    <button id="enableEdit">Enable Content Edit</button>
    <button id="extractText">Extract Text (console)</button>
    <button id="savePdf">Save Edited PDF</button>
  </div>
  <div id="viewer"></div>

  {{-- If you used manual install, serve from /webviewer/lib/webviewer.min.js --}}
  <script src="/webviewer/lib/webviewer.min.js"></script>
  <script>
    (async () => {
      const initialDoc = "{{ $file }}"; // e.g. /storage/sample.pdf

      // If you installed via NPM and are bundling, you’d import WebViewer from '@pdftron/webviewer'
      // For manual/NPM-copied lib, just use the global WebViewer in window.

      const instance = await WebViewer({
        path: '/webviewer/lib',          // where lib/ lives (manual/NPM-copied)
        initialDoc,                      // a URL served by Laravel
        fullAPI: true,                    // needed for extraction APIs
        licenseKey: "demo:1761125977885:6037fde403000000007c5f66550268d9f8309b95b57bf7ba5ed99bb3c2",       // comment out to test content-edit in demo mode
      }, document.getElementById('viewer'));

      const { UI, Core } = instance;
      const { PDFNet, documentViewer } = Core;

      // (1) Enable Content Edit (text & image)
      // Content Edit is an add-on; in demo mode it works if license key is commented out. :contentReference[oaicite:3]{index=3}
      document.getElementById('enableEdit').addEventListener('click', () => {
        UI.enableFeatures(['ContentEdit']); // toggles the text/image WYSIWYG editor
      });

      // (2) Upload and open
      const uploadForm = document.getElementById('uploadForm');
      uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const f = document.getElementById('pdfFile').files[0];
        if (!f) return;
        const fd = new FormData();
        fd.append('pdf', f);
        const res = await fetch('/pdf/apryse/upload', { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }});
        const json = await res.json();
        if (json.ok) {
          UI.loadDocument(json.url);
        }
      });

      // (3) Extract TEXT (logs to console)
      // Requires fullAPI; Apryse has text extraction guide. :contentReference[oaicite:4]{index=4}
      document.getElementById('extractText').addEventListener('click', async () => {
        await PDFNet.initialize();
        const doc = await documentViewer.getDocument().getPDFDoc();
        const reader = await PDFNet.ElementReader.create();
        const itr = await doc.getPageIterator(1);
        let pageNum = 1;
        for (; await itr.hasNext(); await itr.next(), pageNum++) {
          const page = await itr.current();
          await reader.beginOnPage(page);
          let text = '';
          for (let element = await reader.next(); element !== null; element = await reader.next()) {
            if (await element.getType() === PDFNet.Element.Type.e_text) {
              text += await element.getTextString();
            }
          }
          await reader.end();
          console.log(`Page ${pageNum}:`, text);
        }
      });

      // (4) Save edited PDF back to Laravel
      document.getElementById('savePdf').addEventListener('click', async () => {
        const doc = documentViewer.getDocument();
        const xfdfString = await Core.annotationManager.exportAnnotations(); // optional: keep annots
        const data = await doc.getFileData({ xfdfString }); // Uint8Array
        const blob = new Blob([new Uint8Array(data)], { type: 'application/pdf' });

        // Convert to base64 for simple POST
        const b64 = await new Promise((resolve) => {
          const fr = new FileReader();
          fr.onload = () => resolve(fr.result);
          fr.readAsDataURL(blob);
        });

        const filename = (doc?.filename || 'edited.pdf');
        const res = await fetch('/pdf/apryse/save', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
          body: JSON.stringify({ filename, blob: b64 })
        });
        const json = await res.json();
        if (json.ok) {
          alert('Saved: ' + json.url);
          // Optionally redirect or open:
          // window.open(json.url, '_blank');
        }
      });
    })();
  </script>
</body>
</html>
