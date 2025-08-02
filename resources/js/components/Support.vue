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
            <router-link to="/documentation" class="text-gray-500 hover:text-gray-700 transition-colors">Documentation</router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Header -->
      <div class="text-center mb-12">
        <h2 class="text-4xl font-bold bg-gradient-to-r from-gray-900 via-blue-800 to-purple-800 bg-clip-text text-transparent mb-4">
          Support Center
        </h2>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto">
          Need help? We're here to assist you with any questions or issues you might have.
        </p>
      </div>

      <!-- Quick Help -->
      <div class="grid md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 p-6 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">FAQ</h3>
          <p class="text-sm text-gray-600">Find answers to common questions</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 p-6 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Troubleshooting</h3>
          <p class="text-sm text-gray-600">Solve common issues quickly</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 p-6 text-center hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
          <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Contact Us</h3>
          <p class="text-sm text-gray-600">Get in touch for personalized help</p>
        </div>
      </div>

      <!-- FAQ Section -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h3>
        
        <div class="space-y-6">
          <div class="border-b border-gray-200 pb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-3">How do I upload a PDF for editing?</h4>
            <p class="text-gray-700">Click on the "Edit Existing PDF" card on the home page, then drag and drop your PDF file or click to browse and select your file. The PDF will load with all original content preserved.</p>
          </div>

          <div class="border-b border-gray-200 pb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-3">What file formats are supported for images?</h4>
            <p class="text-gray-700">We support common image formats including JPG, PNG, GIF, BMP, and WebP. For best results, use high-resolution images.</p>
          </div>

          <div class="border-b border-gray-200 pb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Can I edit the original PDF content?</h4>
            <p class="text-gray-700">The original PDF content is preserved as a background. You can add new elements on top, but the original text and images cannot be directly edited. This ensures document integrity.</p>
          </div>

          <div class="border-b border-gray-200 pb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-3">How do I copy and paste elements?</h4>
            <p class="text-gray-700">Select any element and press Ctrl+C to copy, then press Ctrl+V to paste. This works for text, images, shapes, and all other elements.</p>
          </div>

          <div class="border-b border-gray-200 pb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-3">What page sizes are available?</h4>
            <p class="text-gray-700">We support A4, Letter, Legal, A3, A5, Tabloid, and Custom sizes. You can also set custom dimensions in points (72 points = 1 inch).</p>
          </div>

          <div class="border-b border-gray-200 pb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-3">How do I export my work?</h4>
            <p class="text-gray-700">Click the "Export PDF" button in the toolbar. You can choose to export the current page only or all pages. The file will download automatically.</p>
          </div>

          <div class="border-b border-gray-200 pb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-3">Is my work saved automatically?</h4>
            <p class="text-gray-700">Currently, work is not automatically saved. We recommend exporting your PDF frequently to avoid losing your work. We're working on adding auto-save functionality.</p>
          </div>

          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-3">What browsers are supported?</h4>
            <p class="text-gray-700">We support modern browsers including Chrome, Firefox, Safari, and Edge. For the best experience, use the latest version of your browser.</p>
          </div>
        </div>
      </div>

      <!-- Troubleshooting -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting Guide</h3>
        
        <div class="grid md:grid-cols-2 gap-8">
          <div class="space-y-6">
            <div>
              <h4 class="text-lg font-semibold text-gray-900 mb-3">PDF Won't Load</h4>
              <ul class="space-y-2 text-gray-700">
                <li>• Ensure the file is a valid PDF format</li>
                <li>• Check that the file size is under 10MB</li>
                <li>• Try refreshing the page and uploading again</li>
                <li>• Clear your browser cache if issues persist</li>
              </ul>
            </div>

            <div>
              <h4 class="text-lg font-semibold text-gray-900 mb-3">Elements Not Visible</h4>
              <ul class="space-y-2 text-gray-700">
                <li>• Check the zoom level - try zooming in or out</li>
                <li>• Ensure elements aren't outside the canvas area</li>
                <li>• Try refreshing the page</li>
                <li>• Check if elements are behind other elements</li>
              </ul>
            </div>

            <div>
              <h4 class="text-lg font-semibold text-gray-900 mb-3">Export Issues</h4>
              <ul class="space-y-2 text-gray-700">
                <li>• Wait for the export process to complete</li>
                <li>• Check your browser's download settings</li>
                <li>• Try a different browser if issues persist</li>
                <li>• Ensure you have sufficient disk space</li>
              </ul>
            </div>
          </div>

          <div class="space-y-6">
            <div>
              <h4 class="text-lg font-semibold text-gray-900 mb-3">Performance Issues</h4>
              <ul class="space-y-2 text-gray-700">
                <li>• Close other browser tabs to free up memory</li>
                <li>• Try reducing the zoom level</li>
                <li>• Restart your browser</li>
                <li>• Use a modern browser with good performance</li>
              </ul>
            </div>

            <div>
              <h4 class="text-lg font-semibold text-gray-900 mb-3">Image Problems</h4>
              <ul class="space-y-2 text-gray-700">
                <li>• Use supported image formats (JPG, PNG, GIF)</li>
                <li>• Try smaller image files if loading is slow</li>
                <li>• Ensure images aren't corrupted</li>
                <li>• Check that images are properly formatted</li>
              </ul>
            </div>

            <div>
              <h4 class="text-lg font-semibold text-gray-900 mb-3">Ruler Not Working</h4>
              <ul class="space-y-2 text-gray-700">
                <li>• Toggle the ruler on/off in the toolbar</li>
                <li>• Ensure you're zoomed in enough to see measurements</li>
                <li>• Try refreshing the page</li>
                <li>• Check that the page has loaded completely</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-xl border border-blue-200/50 p-8 mb-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Contact Us</h3>
        
        <div class="grid md:grid-cols-2 gap-8">
          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Get Help</h4>
            <div class="space-y-4">
              <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                  </svg>
                </div>
                <div>
                  <p class="font-semibold text-gray-900">Email Support</p>
                  <p class="text-sm text-gray-600">support@pdfeditorpro.com</p>
                </div>
              </div>

              <div class="flex items-center">
                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                  </svg>
                </div>
                <div>
                  <p class="font-semibold text-gray-900">Live Chat</p>
                  <p class="text-sm text-gray-600">Available during business hours</p>
                </div>
              </div>

              <div class="flex items-center">
                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                  <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                  </svg>
                </div>
                <div>
                  <p class="font-semibold text-gray-900">Documentation</p>
                  <p class="text-sm text-gray-600">Comprehensive guides and tutorials</p>
                </div>
              </div>
            </div>
          </div>

          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Response Times</h4>
            <div class="space-y-3">
              <div class="flex justify-between items-center">
                <span class="text-gray-700">Email Support</span>
                <span class="text-sm font-semibold text-green-600">Within 24 hours</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-gray-700">Live Chat</span>
                <span class="text-sm font-semibold text-green-600">Immediate</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-gray-700">Documentation</span>
                <span class="text-sm font-semibold text-green-600">Always available</span>
              </div>
            </div>

            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
              <h5 class="font-semibold text-gray-900 mb-2">Before Contacting Support</h5>
              <ul class="text-sm text-gray-700 space-y-1">
                <li>• Check the FAQ section above</li>
                <li>• Review the troubleshooting guide</li>
                <li>• Try refreshing your browser</li>
                <li>• Clear your browser cache</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Feature Requests -->
      <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 p-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Feature Requests & Feedback</h3>
        
        <div class="grid md:grid-cols-2 gap-8">
          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-4">We Value Your Input</h4>
            <p class="text-gray-700 mb-4">
              PDF Editor Pro is constantly evolving based on user feedback. If you have suggestions for new features or improvements, we'd love to hear from you.
            </p>
            <ul class="space-y-2 text-gray-700">
              <li>• New tool suggestions</li>
              <li>• Interface improvements</li>
              <li>• Performance optimizations</li>
              <li>• Bug reports</li>
            </ul>
          </div>

          <div>
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Features</h4>
            <div class="space-y-3">
              <div class="flex items-center">
                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                <span class="text-gray-700">Auto-save functionality</span>
              </div>
              <div class="flex items-center">
                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                <span class="text-gray-700">More font options</span>
              </div>
              <div class="flex items-center">
                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                <span class="text-gray-700">Template library</span>
              </div>
              <div class="flex items-center">
                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                <span class="text-gray-700">Collaborative editing</span>
              </div>
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