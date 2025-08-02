<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-sm border-b border-gray-200/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                  <DocumentTextIcon class="w-5 h-5 text-white" />
                </div>
                <h1 class="ml-3 text-xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">
                  PDF Editor Pro
                </h1>
              </div>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <router-link to="/" class="text-gray-500 hover:text-gray-700 transition-colors">Home</router-link>
            <router-link to="/support" class="text-gray-500 hover:text-gray-700 transition-colors">Support</router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Header -->
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-purple-800 bg-clip-text text-transparent mb-4">
          Documentation
        </h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          Learn how to use PDF Editor Pro effectively with our comprehensive guides and tutorials.
        </p>
      </div>

      <!-- Table of Contents -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Quick Navigation</h3>
        <div class="grid md:grid-cols-2 gap-4">
          <a href="#getting-started" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
              <span class="text-white font-bold text-sm">1</span>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900">Getting Started</h4>
              <p class="text-sm text-gray-600">Basic setup and navigation</p>
            </div>
          </a>
          <a href="#editing-pdfs" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
              <span class="text-white font-bold text-sm">2</span>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900">Editing PDFs</h4>
              <p class="text-sm text-gray-600">Working with existing documents</p>
            </div>
          </a>
          <a href="#creating-pdfs" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
              <span class="text-white font-bold text-sm">3</span>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900">Creating PDFs</h4>
              <p class="text-sm text-gray-600">Building from scratch</p>
            </div>
          </a>
          <a href="#tools" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
              <span class="text-white font-bold text-sm">4</span>
            </div>
            <div>
              <h4 class="font-semibold text-gray-900">Tools & Features</h4>
              <p class="text-sm text-gray-600">All available tools explained</p>
            </div>
          </a>
        </div>
      </div>

      <!-- Getting Started -->
      <div id="getting-started" class="bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Getting Started</h3>
        
        <div class="space-y-6">
          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Uploading a PDF</h4>
            <ol class="list-decimal list-inside space-y-2 text-gray-700">
              <li>Click on the "Edit Existing PDF" card on the home page</li>
              <li>Drag and drop your PDF file or click to browse</li>
              <li>Wait for the PDF to load (you'll see a loading indicator)</li>
              <li>The PDF will appear in the editor with all original content preserved</li>
            </ol>
          </div>

          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Creating a New PDF</h4>
            <ol class="list-decimal list-inside space-y-2 text-gray-700">
              <li>Click on the "Create New PDF" card on the home page</li>
              <li>Choose your page size (A4, Letter, Legal, etc.)</li>
              <li>Select portrait or landscape orientation</li>
              <li>Start adding elements to your blank canvas</li>
            </ol>
          </div>

          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Basic Navigation</h4>
            <ul class="space-y-2 text-gray-700">
              <li><strong>Zoom:</strong> Use the zoom controls in the toolbar (25% - 400%)</li>
              <li><strong>Pan:</strong> Click and drag on empty areas to move around</li>
              <li><strong>Scroll:</strong> Use mouse wheel or scroll bars to navigate large documents</li>
              <li><strong>Rulers:</strong> Toggle rulers for precise measurements</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Editing PDFs -->
      <div id="editing-pdfs" class="bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Editing PDFs</h3>
        
        <div class="space-y-6">
          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Adding Elements</h4>
            <ul class="space-y-2 text-gray-700">
              <li><strong>Text:</strong> Click the text tool, then click on the canvas to add text</li>
              <li><strong>Images:</strong> Click the image tool, select a file, and position it</li>
              <li><strong>Shapes:</strong> Choose rectangle or circle tools, then draw on the canvas</li>
              <li><strong>Signatures:</strong> Use the signature tool to draw your signature</li>
            </ul>
          </div>

          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Editing Elements</h4>
            <ul class="space-y-2 text-gray-700">
              <li><strong>Select:</strong> Click on any element to select it</li>
              <li><strong>Move:</strong> Drag selected elements to reposition them</li>
              <li><strong>Resize:</strong> Use the blue handles around selected elements</li>
              <li><strong>Rotate:</strong> Use the green rotation handle above selected elements</li>
              <li><strong>Properties:</strong> Use the sidebar to change colors, fonts, and styles</li>
            </ul>
          </div>

          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Copy & Paste</h4>
            <ul class="space-y-2 text-gray-700">
              <li><strong>Copy:</strong> Select an element and press Ctrl+C</li>
              <li><strong>Paste:</strong> Press Ctrl+V to paste a copy of the selected element</li>
              <li><strong>Multiple:</strong> You can copy and paste any element type</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Creating PDFs -->
      <div id="creating-pdfs" class="bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Creating PDFs</h3>
        
        <div class="space-y-6">
          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Page Setup</h4>
            <ul class="space-y-2 text-gray-700">
              <li><strong>Page Size:</strong> Choose from A4, Letter, Legal, A3, A5, Tabloid, or Custom</li>
              <li><strong>Orientation:</strong> Switch between Portrait and Landscape</li>
              <li><strong>Custom Size:</strong> Set exact dimensions in points (72 points = 1 inch)</li>
            </ul>
          </div>

          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Design Workflow</h4>
            <ol class="list-decimal list-inside space-y-2 text-gray-700">
              <li>Start with a blank canvas of your chosen size</li>
              <li>Add text elements for titles and content</li>
              <li>Insert images and shapes for visual elements</li>
              <li>Use the grid and rulers for precise alignment</li>
              <li>Export your finished document as a PDF</li>
            </ol>
          </div>
        </div>
      </div>

      <!-- Tools & Features -->
      <div id="tools" class="bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Tools & Features</h3>
        
        <div class="grid md:grid-cols-2 gap-8">
          <div class="space-y-6">
            <div>
              <h4 class="text-lg font-semibold text-gray-900 mb-3">Text Tools</h4>
              <ul class="space-y-2 text-gray-700">
                <li><strong>Font Selection:</strong> Arial, Helvetica, Times New Roman, Courier New</li>
                <li><strong>Text Styling:</strong> Bold, italic, underline, color changes</li>
                <li><strong>Size Control:</strong> Adjustable font size from 8px to 72px</li>
                <li><strong>Background:</strong> Add colored backgrounds to text</li>
              </ul>
            </div>

            <div>
              <h4 class="text-lg font-semibold text-gray-900 mb-3">Shape Tools</h4>
              <ul class="space-y-2 text-gray-700">
                <li><strong>Rectangles:</strong> Add with customizable colors and borders</li>
                <li><strong>Circles:</strong> Perfect circles with fill and stroke options</li>
                <li><strong>Borders:</strong> Adjustable border width and color</li>
                <li><strong>Transparency:</strong> Use transparent backgrounds</li>
              </ul>
            </div>
          </div>

          <div class="space-y-6">
            <div>
              <h4 class="text-lg font-semibold text-gray-900 mb-3">Image Tools</h4>
              <ul class="space-y-2 text-gray-700">
                <li><strong>Upload:</strong> Support for JPG, PNG, GIF, and other formats</li>
                <li><strong>Resize:</strong> Maintain aspect ratio while resizing</li>
                <li><strong>Position:</strong> Drag to any location on the canvas</li>
                <li><strong>Quality:</strong> High-quality rendering in exports</li>
              </ul>
            </div>

            <div>
              <h4 class="text-lg font-semibold text-gray-900 mb-3">Drawing Tools</h4>
              <ul class="space-y-2 text-gray-700">
                <li><strong>Freehand Drawing:</strong> Draw with mouse or touch</li>
                <li><strong>Color Selection:</strong> Choose from any color</li>
                <li><strong>Thickness Control:</strong> Adjust line thickness</li>
                <li><strong>Signature Mode:</strong> Special mode for signatures</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Export Guide -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Exporting Your Work</h3>
        
        <div class="space-y-6">
          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Export Options</h4>
            <ul class="space-y-2 text-gray-700">
              <li><strong>Current Page:</strong> Export only the currently visible page</li>
              <li><strong>All Pages:</strong> Export all pages in the document</li>
              <li><strong>Format:</strong> Always exports as high-quality PDF</li>
              <li><strong>Original Content:</strong> Preserves original PDF content when editing</li>
            </ul>
          </div>

          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Export Process</h4>
            <ol class="list-decimal list-inside space-y-2 text-gray-700">
              <li>Click the "Export PDF" button in the toolbar</li>
              <li>Choose between "Current Page" or "All Pages"</li>
              <li>Wait for the export process to complete</li>
              <li>Your browser will download the PDF file</li>
            </ol>
          </div>
        </div>
      </div>

      <!-- Tips & Tricks -->
      <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-xl border border-blue-200/50 p-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Tips & Tricks</h3>
        
        <div class="grid md:grid-cols-2 gap-6">
          <div class="space-y-4">
            <div class="bg-white rounded-lg p-4 shadow-sm">
              <h4 class="font-semibold text-gray-900 mb-2">Keyboard Shortcuts</h4>
              <ul class="text-sm text-gray-700 space-y-1">
                <li><strong>Ctrl+C:</strong> Copy selected element</li>
                <li><strong>Ctrl+V:</strong> Paste copied element</li>
                <li><strong>Delete:</strong> Remove selected element</li>
                <li><strong>Ctrl+Z:</strong> Undo last action</li>
                <li><strong>Ctrl+Y:</strong> Redo last action</li>
              </ul>
            </div>

            <div class="bg-white rounded-lg p-4 shadow-sm">
              <h4 class="font-semibold text-gray-900 mb-2">Precision Tools</h4>
              <ul class="text-sm text-gray-700 space-y-1">
                <li>Use rulers for exact measurements</li>
                <li>Enable grid for alignment</li>
                <li>Hold Shift while resizing for proportional scaling</li>
                <li>Use zoom for detailed work</li>
              </ul>
            </div>
          </div>

          <div class="space-y-4">
            <div class="bg-white rounded-lg p-4 shadow-sm">
              <h4 class="font-semibold text-gray-900 mb-2">Best Practices</h4>
              <ul class="text-sm text-gray-700 space-y-1">
                <li>Save your work frequently</li>
                <li>Use high-resolution images for better quality</li>
                <li>Test your PDF on different devices</li>
                <li>Keep file sizes reasonable for sharing</li>
              </ul>
            </div>

            <div class="bg-white rounded-lg p-4 shadow-sm">
              <h4 class="font-semibold text-gray-900 mb-2">Troubleshooting</h4>
              <ul class="text-sm text-gray-700 space-y-1">
                <li>If elements don't appear, check the zoom level</li>
                <li>Use undo/redo if something goes wrong</li>
                <li>Refresh the page if the editor becomes unresponsive</li>
                <li>Ensure your browser supports modern web features</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { DocumentTextIcon } from '@heroicons/vue/24/outline'
</script> 