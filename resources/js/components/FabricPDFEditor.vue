<template>
  <div class="fabric-editor">
    <!-- Toolbar is rendered by blade; this component binds logic to existing DOM buttons -->
    <div id="pdf-root"></div>
  </div>
  
</template>

<script>
import * as pdfjsLib from 'pdfjs-dist'
import { fabric } from 'fabric'
import { PDFDocument, rgb } from 'pdf-lib'
import PDFWorker from 'pdfjs-dist/build/pdf.worker.mjs?worker&inline'

export default {
  name: 'FabricPDFEditor',
  props: {
    initialFile: { type: String, default: '' },
  },
  data() {
    return {
      pdfDoc: null,
      pages: [], // { container, pdfCanvas, fabricCanvas, scale, viewport }
      currentZoom: 1,
    }
  },
  mounted() {
    // Configure pdf.js worker using ESM build and Vite inline worker for same-origin blob
    pdfjsLib.GlobalWorkerOptions.workerPort = new PDFWorker()
    const initial = this.initialFile
    if (initial) {
      this.loadPDF({ url: initial })
    }
    this.bindToolbar()
  },
  methods: {
    bindToolbar() {
      const openBtn = document.getElementById('openFile')
      const fileInput = document.getElementById('pdfFile')
      const addText = document.getElementById('addText')
      const addRect = document.getElementById('addRect')
      const addCircle = document.getElementById('addCircle')
      const addImage = document.getElementById('addImage')
      const imageFile = document.getElementById('imageFile')
      const zoomIn = document.getElementById('zoomIn')
      const zoomOut = document.getElementById('zoomOut')
      const exportBtn = document.getElementById('exportPdf')

      if (openBtn && fileInput) {
        openBtn.addEventListener('click', async () => {
          if (!fileInput.files || !fileInput.files[0]) return
          const file = fileInput.files[0]
          const ab = await file.arrayBuffer()
          await this.loadPDF({ data: new Uint8Array(ab) })
        })
      }

      if (addText) addText.addEventListener('click', this.addText)
      if (addRect) addRect.addEventListener('click', this.addRect)
      if (addCircle) addCircle.addEventListener('click', this.addCircle)
      if (addImage && imageFile) {
        addImage.addEventListener('click', () => imageFile.click())
        imageFile.addEventListener('change', this.addImage)
      }
      if (zoomIn) zoomIn.addEventListener('click', () => this.setZoom(this.currentZoom * 1.1))
      if (zoomOut) zoomOut.addEventListener('click', () => this.setZoom(this.currentZoom / 1.1))
      if (exportBtn) exportBtn.addEventListener('click', this.exportPDF)
    },

    async loadPDF(src) {
      this.cleanup()
      const loadingTask = typeof src === 'string' ? pdfjsLib.getDocument({ url: src }) : pdfjsLib.getDocument(src)
      this.pdfDoc = await loadingTask.promise

      const root = document.getElementById('pdf-root')
      root.innerHTML = ''
      this.pages = []

      const num = this.pdfDoc.numPages
      for (let i = 1; i <= num; i++) {
        // container for one page
        const container = document.createElement('div')
        container.className = 'page'

        const pdfCanvas = document.createElement('canvas')
        pdfCanvas.className = 'canvas-layer'
        container.appendChild(pdfCanvas)

        const fabricCanvasEl = document.createElement('canvas')
        fabricCanvasEl.className = 'fabric-layer'
        container.appendChild(fabricCanvasEl)

        root.appendChild(container)

        const page = await this.pdfDoc.getPage(i)
        const viewport = page.getViewport({ scale: this.currentZoom })
        const ctx = pdfCanvas.getContext('2d')
        pdfCanvas.width = viewport.width
        pdfCanvas.height = viewport.height

        await page.render({ canvasContext: ctx, viewport }).promise

        // init fabric canvas sized to pdf canvas
        fabricCanvasEl.width = viewport.width
        fabricCanvasEl.height = viewport.height
        const fabricCanvas = new fabric.Canvas(fabricCanvasEl, {
          selection: true,
          preserveObjectStacking: true,
        })

        // Enable panning with spacebar+drag
        let isPanning = false
        fabricCanvas.on('mouse:down', (opt) => {
          const evt = opt.e
          if (evt.spaceKey || evt.code === 'Space' || evt.which === 32 || evt.keyCode === 32) {
            isPanning = true
            fabricCanvas.setCursor('grab')
          }
        })
        fabricCanvas.on('mouse:up', () => {
          isPanning = false
          fabricCanvas.setCursor('default')
        })
        fabricCanvas.on('mouse:move', (opt) => {
          if (isPanning && opt && opt.e) {
            const delta = new fabric.Point(opt.e.movementX, opt.e.movementY)
            fabricCanvas.relativePan(delta)
          }
        })

        this.pages.push({ container, pdfCanvas, fabricCanvas, scale: this.currentZoom, viewport })
      }
    },

    setZoom(zoom) {
      this.currentZoom = Math.max(0.1, Math.min(5, zoom))
      this.rerenderAllPages()
    },

    async rerenderAllPages() {
      if (!this.pdfDoc) return
      for (let i = 0; i < this.pages.length; i++) {
        const pageIndex = i + 1
        const page = await this.pdfDoc.getPage(pageIndex)
        const { pdfCanvas, fabricCanvas, container } = this.pages[i]
        const viewport = page.getViewport({ scale: this.currentZoom })
        const ctx = pdfCanvas.getContext('2d')
        pdfCanvas.width = viewport.width
        pdfCanvas.height = viewport.height
        await page.render({ canvasContext: ctx, viewport }).promise

        // resize fabric canvas and scale objects
        const prevWidth = fabricCanvas.getWidth()
        const prevHeight = fabricCanvas.getHeight()
        const newW = viewport.width
        const newH = viewport.height
        const scaleX = newW / prevWidth
        const scaleY = newH / prevHeight

        fabricCanvas.getObjects().forEach(obj => {
          obj.scaleX = (obj.scaleX || 1) * scaleX
          obj.scaleY = (obj.scaleY || 1) * scaleY
          obj.left = (obj.left || 0) * scaleX
          obj.top = (obj.top || 0) * scaleY
          obj.setCoords()
        })
        fabricCanvas.setWidth(newW)
        fabricCanvas.setHeight(newH)
        container.style.width = newW + 'px'
        container.style.height = newH + 'px'
        fabricCanvas.requestRenderAll()

        this.pages[i].viewport = viewport
        this.pages[i].scale = this.currentZoom
      }
    },

    addText: function () {
      if (!this.pages.length) return
      const text = new fabric.IText('Double-click to edit', {
        left: 50,
        top: 50,
        fontSize: 18,
        fill: '#000',
      })
      this.pages[0].fabricCanvas.add(text).setActiveObject(text)
      this.pages[0].fabricCanvas.requestRenderAll()
    },

    addRect() {
      if (!this.pages.length) return
      const rect = new fabric.Rect({ left: 80, top: 80, width: 120, height: 80, fill: 'rgba(255,0,0,0.2)', stroke: 'red' })
      this.pages[0].fabricCanvas.add(rect).setActiveObject(rect)
      this.pages[0].fabricCanvas.requestRenderAll()
    },

    addCircle() {
      if (!this.pages.length) return
      const circle = new fabric.Circle({ left: 160, top: 120, radius: 50, fill: 'rgba(0,0,255,0.2)', stroke: 'blue' })
      this.pages[0].fabricCanvas.add(circle).setActiveObject(circle)
      this.pages[0].fabricCanvas.requestRenderAll()
    },

    addImage: function (evt) {
      if (!this.pages.length) return
      const input = evt?.target || document.getElementById('imageFile')
      const file = input?.files?.[0]
      if (!file) return
      const url = URL.createObjectURL(file)
      fabric.Image.fromURL(url, (img) => {
        img.set({ left: 100, top: 100, scaleX: 0.5, scaleY: 0.5 })
        this.pages[0].fabricCanvas.add(img).setActiveObject(img)
        this.pages[0].fabricCanvas.requestRenderAll()
      })
    },

    async exportPDF() {
      if (!this.pdfDoc) return
      const originalBuffer = await (await fetch(this.initialFile)).arrayBuffer().catch(() => null)
      // Fallback: if loading from input blob URL, re-fetch via canvas render
      let pdfBytes
      if (originalBuffer) {
        pdfBytes = new Uint8Array(originalBuffer)
      } else {
        // Attempt to serialize the loaded pdfDoc from pdf.js if needed (not directly available)
        // As a simple fallback, ask the user to open via server URL for export.
        alert('For export, open a server URL or initial file.')
        return
      }

      const outDoc = await PDFDocument.load(pdfBytes)
      for (let i = 0; i < this.pages.length; i++) {
        const page = outDoc.getPage(i)
        const { fabricCanvas, viewport } = this.pages[i]
        // Render overlay as PNG
        const dataUrl = fabricCanvas.toDataURL({ format: 'png', multiplier: 1 })
        const pngBytes = await fetch(dataUrl).then(r => r.arrayBuffer())
        const png = await outDoc.embedPng(pngBytes)
        const pngDims = png.scale(1)
        // Map canvas pixels to PDF points: assume 1 CSS px ~ 1 pt for simplicity
        const { width, height } = page.getSize()
        // pdf.js renders top-left origin; PDF has bottom-left origin → flip vertically
        page.drawImage(png, {
          x: 0,
          y: 0,
          width: width,
          height: height,
          opacity: 1,
        })
      }

      const outBytes = await outDoc.save()
      const blob = new Blob([outBytes], { type: 'application/pdf' })
      const url = URL.createObjectURL(blob)

      // Offer download
      const a = document.createElement('a')
      a.href = url
      a.download = 'edited.pdf'
      a.click()
      URL.revokeObjectURL(url)
    },

    cleanup() {
      if (this.pages?.length) {
        for (const p of this.pages) {
          try { p.fabricCanvas.dispose() } catch (_) {}
        }
      }
      this.pages = []
    }
  },
  beforeUnmount() {
    this.cleanup()
  }
}
</script>

<style scoped>
.fabric-editor {
  height: 100%;
}
#pdf-root {
  height: 100%;
  overflow: auto;
}
.page {
  position: relative;
}
</style>


