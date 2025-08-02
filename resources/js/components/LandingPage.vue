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
            <router-link to="/documentation" class="text-gray-500 hover:text-gray-700 transition-colors">Documentation</router-link>
            <router-link to="/support" class="text-gray-500 hover:text-gray-700 transition-colors">Support</router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Hero Section -->
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-6xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-purple-800 bg-clip-text text-transparent mb-6">
          Create & Edit PDFs
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
          Transform your documents with our powerful PDF editor. Upload existing PDFs or create new ones from scratch with professional-grade tools.
        </p>
      </div>

      <!-- Action Cards -->
      <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-16">
        <!-- Edit Existing PDF -->
        <div class="group relative flex">
          <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-300"></div>
          <div class="relative bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 flex flex-col w-full">
            <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl mb-6 mx-auto">
              <ArrowUpTrayIcon class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Edit Existing PDF</h3>
            <p class="text-gray-600 mb-6 text-center leading-relaxed">
              Upload your PDF and enhance it with text, images, shapes, and signatures while preserving the original layout.
            </p>
            
            <!-- Features List -->
            <div class="space-y-3 mb-8 flex-1">
              <div class="flex items-center text-sm text-gray-600">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                <span>Preserve original layout</span>
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                <span>Add text overlays</span>
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                <span>Insert images & shapes</span>
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                <span>Digital signatures</span>
              </div>
            </div>

            <!-- File Upload Area -->
            <div 
              @click="triggerFileUpload"
              @dragover.prevent
              @drop.prevent="handleFileDrop"
              class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 hover:bg-blue-50/50 transition-all duration-200 cursor-pointer group"
            >
              <ArrowUpTrayIcon class="w-8 h-8 text-gray-400 mx-auto mb-3 group-hover:text-blue-500 transition-colors" />
              <p class="text-sm text-gray-600 mb-2">
                <span class="font-medium text-blue-600">Click to upload</span> or drag and drop
              </p>
              <p class="text-xs text-gray-500">PDF files only (max 10MB)</p>
            </div>
            
            <input 
              ref="fileInput"
              type="file" 
              accept=".pdf"
              @change="handleFileUpload"
              class="hidden"
            />
          </div>
        </div>

        <!-- Create New PDF -->
        <div class="group relative flex">
          <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-300"></div>
          <div class="relative bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 flex flex-col w-full">
            <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl mb-6 mx-auto">
              <PlusIcon class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Create New PDF</h3>
            <p class="text-gray-600 mb-6 text-center leading-relaxed">
              Start from scratch and build custom PDF documents with professional design tools and templates.
            </p>
            
            <!-- Features List -->
            <div class="space-y-3 mb-8 flex-1">
              <div class="flex items-center text-sm text-gray-600">
                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                <span>Choose page size (A4, Letter, Custom)</span>
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                <span>Add text blocks with custom styling</span>
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                <span>Upload and position images</span>
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                <span>Draw shapes with color controls</span>
              </div>
            </div>

            <button 
              @click="handleCreateNew"
              class="w-full bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl"
            >
              <div class="flex items-center justify-center">
                <PlusIcon class="w-5 h-5 mr-2" />
                Create New PDF
              </div>
            </button>
          </div>
        </div>
      </div>

      <!-- Features Section -->
      <div class="relative">
        <!-- Background decoration -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-purple-50 rounded-3xl"></div>
        <div class="absolute top-0 left-1/4 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
        <div class="absolute top-0 right-1/4 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-1000"></div>
        <div class="absolute -bottom-8 left-1/3 w-72 h-72 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
        
        <div class="relative bg-white/70 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 p-12">
          <!-- Header -->
          <div class="text-center mb-16">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl mb-6 shadow-lg">
              <DocumentTextIcon class="w-10 h-10 text-white" />
            </div>
            <h3 class="text-3xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-purple-800 bg-clip-text text-transparent mb-4">
              Powerful Features
            </h3>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
              Everything you need to create professional PDF documents with precision and style
            </p>
          </div>

          <!-- Feature Categories -->
          <div class="grid lg:grid-cols-3 gap-8">
            <!-- Common Features -->
            <div class="group">
              <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl p-8 h-full border border-blue-200/30 hover:border-blue-300/50 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="flex items-center mb-6">
                  <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <h4 class="text-xl font-bold text-gray-900">Core Tools</h4>
                </div>
                <ul class="space-y-4">
                  <li class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <span class="font-semibold text-gray-900">Zoom & Pan</span>
                      <p class="text-sm text-gray-600 mt-1">Navigate documents with precision (25% - 400%)</p>
                    </div>
                  </li>
                  <li class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <span class="font-semibold text-gray-900">Professional Rulers</span>
                      <p class="text-sm text-gray-600 mt-1">Inch-based measurements with detailed markings</p>
                    </div>
                  </li>
                  <li class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <span class="font-semibold text-gray-900">PDF Import & Export</span>
                      <p class="text-sm text-gray-600 mt-1">Load existing PDFs and export with original content</p>
                    </div>
                  </li>
                  <li class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <span class="font-semibold text-gray-900">Page Size Support</span>
                      <p class="text-sm text-gray-600 mt-1">A4, Letter, Legal, A3, A5, Tabloid, and Custom sizes</p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Element Editing -->
            <div class="group">
              <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-2xl p-8 h-full border border-purple-200/30 hover:border-purple-300/50 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="flex items-center mb-6">
                  <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                    </svg>
                  </div>
                  <h4 class="text-xl font-bold text-gray-900">Design Tools</h4>
                </div>
                <ul class="space-y-4">
                  <li class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <span class="font-semibold text-gray-900">Text Elements</span>
                      <p class="text-sm text-gray-600 mt-1">Rich text with fonts, colors, and styling</p>
                    </div>
                  </li>
                  <li class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <span class="font-semibold text-gray-900">Shapes & Images</span>
                      <p class="text-sm text-gray-600 mt-1">Rectangles, circles, and image uploads</p>
                    </div>
                  </li>
                  <li class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <span class="font-semibold text-gray-900">Digital Signatures</span>
                      <p class="text-sm text-gray-600 mt-1">Draw and add custom signatures</p>
                    </div>
                  </li>
                  <li class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <span class="font-semibold text-gray-900">Copy & Paste</span>
                      <p class="text-sm text-gray-600 mt-1">Ctrl+C and Ctrl+V for all elements</p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Advanced Options -->
            <div class="group">
              <div class="bg-gradient-to-br from-green-50 to-green-100/50 rounded-2xl p-8 h-full border border-green-200/30 hover:border-green-300/50 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="flex items-center mb-6">
                  <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                  </div>
                  <h4 class="text-xl font-bold text-gray-900">Advanced Features</h4>
                </div>
                <ul class="space-y-4">
                  <li class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <span class="font-semibold text-gray-900">Undo & Redo</span>
                      <p class="text-sm text-gray-600 mt-1">Full history tracking for all changes</p>
                    </div>
                  </li>
                  <li class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <span class="font-semibold text-gray-900">Drawing Tools</span>
                      <p class="text-sm text-gray-600 mt-1">Freehand drawing with custom colors</p>
                    </div>
                  </li>
                  <li class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <span class="font-semibold text-gray-900">Export Options</span>
                      <p class="text-sm text-gray-600 mt-1">Current page or all pages export</p>
                    </div>
                  </li>
                  <li class="flex items-start">
                    <div class="flex-shrink-0 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3 mt-0.5">
                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                    </div>
                    <div>
                      <span class="font-semibold text-gray-900">Scrollable Canvas</span>
                      <p class="text-sm text-gray-600 mt-1">Navigate large documents easily</p>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Call to Action -->
          <div class="text-center mt-12">
            <div class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full text-sm font-medium shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
              Ready to get started?
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { ArrowUpTrayIcon, PlusIcon, DocumentTextIcon } from '@heroicons/vue/24/outline'

const router = useRouter()
const fileInput = ref(null)

const triggerFileUpload = () => {
  fileInput.value?.click()
}

const handleFileUpload = (event) => {
  const file = event.target.files?.[0] || event.dataTransfer?.files?.[0]
  if (file && file.type === 'application/pdf') {
    // Store file in localStorage as a temporary solution
    const reader = new FileReader()
    reader.onload = () => {
      localStorage.setItem('uploadedPdfData', reader.result)
      localStorage.setItem('uploadedPdfName', file.name)
      localStorage.setItem('uploadedPdfSize', file.size.toString())
      
      router.push({
        path: '/editor',
        query: { mode: 'edit' }
      })
    }
    reader.readAsDataURL(file)
  }
}

const handleFileDrop = (event) => {
  handleFileUpload(event)
}

const handleCreateNew = () => {
  router.push({
    path: '/editor',
    query: { mode: 'create' }
  })
}
</script> 