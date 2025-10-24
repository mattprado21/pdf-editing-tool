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
    <input type="file" id="pdfFile" accept="application/pdf" style="display:none" />
    <button id="chooseUpload" style="padding:6px 10px;border:1px solid #ccc;border-radius:6px;background:#f8f9fa;cursor:pointer">Choose & Upload</button>
    <button id="extractText" style="padding:6px 10px;border:1px solid #ccc;border-radius:6px;background:#f8f9fa;cursor:pointer">Extract Text</button>
    <button id="downloadPdf" style="padding:6px 10px;border:1px solid #ccc;border-radius:6px;background:#10b981;color:white;cursor:pointer">Download PDF</button>
  </div>
  <div id="viewer"></div>

  <!-- Simple modal for extracted text -->
  <div id="textModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);align-items:center;justify-content:center;">
    <div style="background:#fff;max-width:800px;width:90%;max-height:80vh;border-radius:8px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.2);">
      <div style="padding:10px 14px;border-bottom:1px solid #eee;display:flex;align-items:center;justify-content:space-between;">
        <strong>Extracted Text</strong>
        <div>
          <button id="downloadTextBtn" style="margin-right:8px;padding:6px 10px;border:1px solid #ccc;border-radius:6px;background:#f8f9fa;cursor:pointer">Download .txt</button>
          <button id="closeTextModal" style="padding:6px 10px;border:1px solid #ccc;border-radius:6px;background:#f8f9fa;cursor:pointer">Close</button>
        </div>
      </div>
      <textarea id="extractedTextArea" readonly style="width:100%;height:60vh;padding:12px;border:0;outline:none;resize:none"></textarea>
    </div>
  </div>

  {{-- If you used manual install, serve from /webviewer/lib/webviewer.min.js --}}
  <script src="/webviewer/lib/webviewer.min.js"></script>
  <script>
    (async () => {
      const initialDoc = "{{ $file }}"; // optional

      // If you installed via NPM and are bundling, you’d import WebViewer from '@pdftron/webviewer'
      // For manual/NPM-copied lib, just use the global WebViewer in window.

      const viewerConfig = {
        path: '/webviewer/lib',
        fullAPI: true,
        licenseKey: "demo:1761125977885:6037fde403000000007c5f66550268d9f8309b95b57bf7ba5ed99bb3c2",
      };
      if (initialDoc) viewerConfig.initialDoc = initialDoc;
      const instance = await WebViewer(viewerConfig, document.getElementById('viewer'));

      instance.UI.setLanguage('en');

      const { UI, Core } = instance;
      const { PDFNet, documentViewer } = Core;

      // Always enable Content Edit on load (if available in your build/license)
      try { UI.enableFeatures(['ContentEdit']); } catch (e) { /* ignore if unavailable */ }

      // Choose & Upload flow (single button opens picker then uploads)
      const chooseBtn = document.getElementById('chooseUpload');
      const fileInput = document.getElementById('pdfFile');
      chooseBtn.addEventListener('click', () => fileInput.click());
      fileInput.addEventListener('change', async () => {
        const f = fileInput.files[0];
        if (!f) return;
        const fd = new FormData();
        fd.append('pdf', f);
        const res = await fetch('/pdf/apryse/upload', { method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }});
        const json = await res.json();
        if (json.ok) {
          UI.loadDocument(json.url);
        }
      });

      // Extract TEXT → show in modal and allow download
      const textModal = document.getElementById('textModal');
      const textArea = document.getElementById('extractedTextArea');
      const closeModal = document.getElementById('closeTextModal');
      const downloadBtn = document.getElementById('downloadTextBtn');
      closeModal.addEventListener('click', () => { textModal.style.display = 'none'; });
      downloadBtn.addEventListener('click', () => {
        const blob = new Blob([textArea.value || ''], { type: 'text/plain;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'extracted.txt';
        a.click();
        URL.revokeObjectURL(url);
      });

      document.getElementById('extractText').addEventListener('click', async () => {
        await PDFNet.initialize();
        const doc = await documentViewer.getDocument().getPDFDoc();
        const reader = await PDFNet.ElementReader.create();
        const itr = await doc.getPageIterator(1);
        let pageNum = 1;
        let all = '';
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
          all += `\n\n--- Page ${pageNum} ---\n` + text;
        }
        textArea.value = all.trim();
        textModal.style.display = 'flex';
      });

      // (4a) Download edited PDF locally
      document.getElementById('downloadPdf').addEventListener('click', async () => {
        const doc = documentViewer.getDocument();
        const xfdfString = await Core.annotationManager.exportAnnotations();
        const data = await doc.getFileData({ xfdfString });
        const blob = new Blob([new Uint8Array(data)], { type: 'application/pdf' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = (doc?.filename ? `edited_${doc.filename}` : 'edited.pdf');
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
      });

      // Removed server save; download-only
    })();
  </script>
</body>
</html>
