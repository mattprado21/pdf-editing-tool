<template>
  <div class="min-h-screen bg-gray-50" style="overflow: hidden;">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 shadow-sm">
      <div class="px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-6">
            <button 
              @click="goBack"
              class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200 hover:shadow-sm"
            >
              <ArrowLeftIcon class="w-4 h-4" />
              Back to Home
            </button>
            <div class="h-6 w-px bg-gray-300"></div>
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                <DocumentTextIcon class="w-5 h-5 text-white" />
              </div>
              <div>
                <h1 class="text-xl font-bold text-gray-900">{{ mode === 'edit' ? 'Edit PDF' : 'Create PDF' }}</h1>
                <p class="text-sm text-gray-500">{{ mode === 'edit' ? 'Modify existing document' : 'Build from scratch' }}</p>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-4">
            <!-- Page Settings -->
            <div class="flex items-center gap-2">
              <span class="text-sm font-medium text-gray-700">Page Size:</span>
              <select 
                v-model="selectedPageSize"
                @change="updatePageSize"
                class="px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
              >
                <option value="custom">Custom</option>
                <option value="a4">A4 (8.27" × 11.69")</option>
                <option value="letter">Letter (8.5" × 11")</option>
                <option value="legal">Legal (8.5" × 14")</option>
                <option value="a3">A3 (11.69" × 16.54")</option>
                <option value="a5">A5 (5.83" × 8.27")</option>
                <option value="tabloid">Tabloid (11" × 17")</option>
              </select>
            </div>
            
            <div class="flex items-center gap-2">
              <span class="text-sm font-medium text-gray-700">Orientation:</span>
              <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                <button 
                  @click="setOrientation('portrait')"
                  :class="[
                    'px-3 py-1 text-sm transition-colors',
                    pageOrientation === 'portrait' 
                      ? 'bg-blue-500 text-white' 
                      : 'bg-white text-gray-700 hover:bg-gray-50'
                  ]"
                >
                  Portrait
                </button>
                <button 
                  @click="setOrientation('landscape')"
                  :class="[
                    'px-3 py-1 text-sm transition-colors',
                    pageOrientation === 'landscape' 
                      ? 'bg-blue-500 text-white' 
                      : 'bg-white text-gray-700 hover:bg-gray-50'
                  ]"
                >
                  Landscape
                </button>
              </div>
            </div>

            <!-- Undo/Redo -->
            <div class="flex items-center gap-2">
              <button 
                @click="undo"
                :disabled="historyIndex <= 0"
                class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <ArrowUturnLeftIcon class="w-5 h-5" />
              </button>
              <button 
                @click="redo"
                :disabled="historyIndex >= history.length - 1"
                class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <ArrowUturnRightIcon class="w-5 h-5" />
              </button>
            </div>

            <!-- Copy/Paste -->
            <div class="flex items-center gap-2">
              <button 
                @click="copyElement"
                :disabled="!selectedElement"
                class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                title="Copy (Ctrl+C)"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
              </button>
              <button 
                @click="pasteElement"
                :disabled="!clipboard"
                class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                :class="clipboard ? 'text-green-600 hover:text-green-700' : ''"
                title="Paste (Ctrl+V)"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
              </button>
            </div>

            <div class="w-px h-6 bg-gray-300"></div>

            <!-- View Controls -->
            <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-1">
              <button 
                @click="showGrid = !showGrid"
                :class="[
                  'p-2 rounded-md transition-all duration-200',
                  showGrid ? 'bg-white shadow-sm text-blue-600' : 'text-gray-600 hover:bg-white'
                ]"
                title="Toggle Grid"
              >
                <ViewColumnsIcon class="w-4 h-4" />
              </button>
              <!-- Ruler Toggle -->
              <button 
                @click="showRuler = !showRuler"
                :class="[
                  'p-2 rounded-lg transition-colors',
                  showRuler ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
                ]"
              >
                <Square3Stack3DIcon class="w-5 h-5" />
              </button>
              <!-- Text Overlay Toggle -->
              <button 
                @click="showTextOverlay = !showTextOverlay"
                :class="[
                  'p-2 rounded-lg transition-colors',
                  showTextOverlay ? 'bg-blue-100 text-blue-600' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
                ]"
                title="Toggle Text Overlay"
              >
                <DocumentTextIcon class="w-5 h-5" />
              </button>
            </div>

            <div class="w-px h-6 bg-gray-300"></div>

            <!-- Actions -->
            <div class="flex items-center gap-2">
              <!-- Save button hidden until login functionality is implemented -->
              <!-- <button 
                @click="saveProject"
                class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200"
              >
                <ArrowDownTrayIcon class="w-4 h-4" />
                Save
              </button> -->
              
              <!-- Export Options -->
              <div class="flex items-center gap-2">
                <select 
                  v-model="exportMode"
                  class="px-3 py-2 pr-8 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white"
                  style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%204.9A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.1c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.4-12.8z%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 8px center; background-size: 12px auto;"
                >
                  <option value="current">Current Page</option>
                  <option value="all">All Pages</option>
                </select>
                <button 
                  @click="exportToPDF"
                  class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                >
                  <ArrowDownTrayIcon class="w-4 h-4" />
                  Export PDF
                </button>
              </div>
            </div>
            </div>
          </div>
        </div>
      </div>

    <div class="flex h-[calc(100vh-88px)]">
      <!-- Left Sidebar - Tools -->
      <div class="w-80 bg-white border-r border-gray-200 overflow-y-auto">
        <div class="p-6">
          <!-- Tab Navigation -->
          <div class="mb-6">
            <div class="flex bg-gray-100 rounded-xl p-1">
              <button 
                @click="activeTab = 'elements'"
                :class="[
                  'flex-1 py-2.5 px-4 text-sm font-medium rounded-lg transition-all duration-200',
                  activeTab === 'elements' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-600 hover:text-gray-900'
                ]"
              >
                Elements
              </button>
              <button 
                @click="activeTab = 'pages'"
                :class="[
                  'flex-1 py-2.5 px-4 text-sm font-medium rounded-lg transition-all duration-200',
                  activeTab === 'pages' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-600 hover:text-gray-900'
                ]"
              >
                Pages
              </button>
              <button 
                @click="activeTab = 'properties'"
                :class="[
                  'flex-1 py-2.5 px-4 text-sm font-medium rounded-lg transition-all duration-200',
                  activeTab === 'properties' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-600 hover:text-gray-900'
                ]"
              >
                Properties
              </button>
            </div>
          </div>

          <!-- Elements Tab -->
          <div v-if="activeTab === 'elements'" class="space-y-4">
            <!-- Add Elements -->
            <div class="space-y-3">
              <h3 class="text-sm font-medium text-gray-700">Add Elements</h3>
              <div class="grid grid-cols-2 gap-3">
                <button 
                  @click="addElement('text')"
                  class="flex flex-col items-center p-4 bg-white border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200"
                >
                  <DocumentTextIcon class="w-6 h-6 text-gray-600 mb-2" />
                  <span class="text-xs text-gray-600">Text</span>
                </button>
                <button 
                  @click="addElement('image')"
                  class="flex flex-col items-center p-4 bg-white border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200"
                >
                  <PhotoIcon class="w-6 h-6 text-gray-600 mb-2" />
                  <span class="text-xs text-gray-600">Image</span>
                </button>
                <button 
                  @click="addElement('rectangle')"
                  class="flex flex-col items-center p-4 bg-white border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200"
                >
                  <Square2StackIcon class="w-6 h-6 text-gray-600 mb-2" />
                  <span class="text-xs text-gray-600">Rectangle</span>
                </button>
                <button 
                  @click="addElement('circle')"
                  class="flex flex-col items-center p-4 bg-white border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200"
                >
                  <CircleStackIcon class="w-6 h-6 text-gray-600 mb-2" />
                  <span class="text-xs text-gray-600">Circle</span>
                </button>
                <button 
                  @click="openSignatureModal"
                  class="flex flex-col items-center p-4 bg-white border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-all duration-200"
                >
                  <PencilIcon class="w-6 h-6 text-gray-600 mb-2" />
                  <span class="text-xs text-gray-600">Sign</span>
                </button>
              </div>
            </div>


          </div>

          <!-- Pages Tab -->
          <div v-if="activeTab === 'pages'" class="space-y-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-semibold text-gray-900">Pages</h3>
              <button 
                @click="addPage"
                class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200"
              >
                <PlusIcon class="w-4 h-4" />
                Add Page
              </button>
            </div>
            
            <div class="space-y-2">
              <div 
                v-for="(page, index) in pages" 
                :key="index"
                @click="currentPageIndex = index"
                :class="[
                  'flex items-center gap-3 p-3 rounded-lg cursor-pointer transition-all duration-200',
                  currentPageIndex === index ? 'bg-blue-50 border border-blue-200' : 'hover:bg-gray-50'
                ]"
              >
                <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center">
                  <span class="text-sm font-medium text-gray-600">{{ index + 1 }}</span>
                </div>
                <div class="flex-1">
                  <p class="text-sm font-medium text-gray-900">Page {{ index + 1 }}</p>
                  <p class="text-xs text-gray-500">{{ page.elements.length }} elements</p>
                </div>
                <div class="flex items-center gap-1">
                  <button 
                    @click.stop="duplicatePage(index)"
                    class="p-1 text-gray-400 hover:text-gray-600 transition-colors"
                    title="Duplicate Page"
                  >
                    <DocumentIcon class="w-4 h-4" />
                  </button>
                  <button 
                    v-if="pages.length > 1"
                    @click.stop="deletePage(index)"
                    class="p-1 text-gray-400 hover:text-red-600 transition-colors"
                    title="Delete Page"
                  >
                    <TrashIcon class="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Properties Tab -->
          <div v-if="activeTab === 'properties' && selectedElement" class="space-y-4">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold text-gray-900">Element Properties</h3>
              <div class="flex items-center gap-2">
                <button
                  @click="copyElement"
                  class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                  title="Copy element (Ctrl+C)"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                  </svg>
                </button>
                <button
                  v-if="clipboard"
                  @click="pasteElement"
                  class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                  title="Paste element (Ctrl+V)"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </button>
                <button
                  @click="deleteSelectedElement"
                  class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                  title="Delete element"
                >
                  <TrashIcon class="w-5 h-5" />
                </button>
              </div>
            </div>
            
            <!-- Layer Controls -->
            <div class="space-y-2">
              <label class="text-sm font-medium text-gray-700">Layer</label>
              <div class="flex gap-2">
                <button
                  @click="sendToBack"
                  class="flex-1 px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors"
                >
                  Send to Back
                </button>
                <button
                  @click="sendToFront"
                  class="flex-1 px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors"
                >
                  Send to Front
                </button>
              </div>
            </div>
            
            <div class="space-y-4">
              <!-- Position -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                <div class="grid grid-cols-2 gap-2">
                  <div>
                    <label class="block text-xs text-gray-500 mb-1">X</label>
                    <input 
                      v-model.number="selectedElement.x" 
                      type="number" 
                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label class="block text-xs text-gray-500 mb-1">Y</label>
                    <input 
                      v-model.number="selectedElement.y" 
                      type="number" 
                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                  </div>
                </div>
              </div>

              <!-- Size -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Size</label>
                <div class="grid grid-cols-2 gap-2">
                  <div>
                    <label class="block text-xs text-gray-500 mb-1">Width</label>
                    <input 
                      v-model.number="selectedElement.width" 
                      type="number" 
                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label class="block text-xs text-gray-500 mb-1">Height</label>
                    <input 
                      v-model.number="selectedElement.height" 
                      type="number" 
                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                  </div>
                </div>
              </div>

              <!-- Text Properties -->
              <div v-if="selectedElement.type === 'text'">
                <label class="block text-sm font-medium text-gray-700 mb-2">Text Content</label>
                <textarea 
                  v-model="selectedElement.content" 
                  rows="3"
                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                ></textarea>
                
                <!-- Font Controls -->
                <div class="grid grid-cols-2 gap-2 mt-3">
                  <div>
                    <label class="block text-xs text-gray-500 mb-1">Font Size</label>
                    <input 
                      v-model.number="selectedElement.fontSize" 
                      type="number" 
                      min="8"
                      max="72"
                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                  </div>
                  <div>
                    <label class="block text-xs text-gray-500 mb-1">Font Family</label>
                    <select 
                      v-model="selectedElement.fontFamily"
                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                      <option value="Arial">Arial</option>
                      <option value="Times New Roman">Times New Roman</option>
                      <option value="Helvetica">Helvetica</option>
                      <option value="Courier New">Courier New</option>
                    </select>
                  </div>
                </div>

                <!-- Text Style Controls -->
                <div class="mt-3">
                  <label class="block text-xs text-gray-500 mb-2">Text Style</label>
                  <div class="flex gap-2">
                    <button 
                      @click="selectedElement.fontWeight = selectedElement.fontWeight === 'bold' ? 'normal' : 'bold'"
                      :class="[
                        'px-3 py-1.5 text-xs font-medium rounded border transition-colors',
                        selectedElement.fontWeight === 'bold' 
                          ? 'bg-blue-500 text-white border-blue-500' 
                          : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200'
                      ]"
                    >
                      Bold
                    </button>
                    <button 
                      @click="selectedElement.fontStyle = selectedElement.fontStyle === 'italic' ? 'normal' : 'italic'"
                      :class="[
                        'px-3 py-1.5 text-xs font-medium rounded border transition-colors',
                        selectedElement.fontStyle === 'italic' 
                          ? 'bg-blue-500 text-white border-blue-500' 
                          : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200'
                      ]"
                    >
                      Italic
                    </button>
                    <button 
                      @click="selectedElement.textDecoration = selectedElement.textDecoration === 'underline' ? 'none' : 'underline'"
                      :class="[
                        'px-3 py-1.5 text-xs font-medium rounded border transition-colors',
                        selectedElement.textDecoration === 'underline' 
                          ? 'bg-blue-500 text-white border-blue-500' 
                          : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200'
                      ]"
                    >
                      Underline
                    </button>
                  </div>
                </div>
              </div>

              <!-- Drawing Properties -->
              <div v-if="selectedElement.type === 'drawing'">
                <label class="block text-sm font-medium text-gray-700 mb-2">Drawing Settings</label>
                <div class="space-y-3">
                  <div>
                    <label class="block text-xs text-gray-500 mb-1">Color</label>
                    <input 
                      v-model="selectedElement.color" 
                      type="color" 
                      class="w-full h-10 border border-gray-300 rounded-lg cursor-pointer"
                    />
                  </div>
                  <div>
                    <label class="block text-xs text-gray-500 mb-1">Thickness: {{ selectedElement.thickness }}px</label>
                    <input 
                      v-model.number="selectedElement.thickness" 
                      type="range" 
                      min="1"
                      max="20"
                      class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                    />
                  </div>
                </div>
              </div>

              <!-- Colors -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Colors</label>
                <div class="space-y-2">
                  <div v-if="selectedElement.type === 'text'">
                    <label class="block text-xs text-gray-500 mb-1">Text Color</label>
                    <input 
                      v-model="selectedElement.color" 
                      type="color" 
                      class="w-full h-10 border border-gray-300 rounded-lg cursor-pointer"
                    />
                  </div>
                  <div v-if="selectedElement.type === 'rectangle' || selectedElement.type === 'circle'">
                    <label class="block text-xs text-gray-500 mb-1">Background Color</label>
                    <input 
                      v-model="selectedElement.backgroundColor" 
                      type="color" 
                      class="w-full h-10 border border-gray-300 rounded-lg cursor-pointer"
                    />
                  </div>
                  <div v-if="selectedElement.type === 'rectangle' || selectedElement.type === 'circle'">
                    <label class="block text-xs text-gray-500 mb-1">Border Color</label>
                    <input 
                      v-model="selectedElement.borderColor" 
                      type="color" 
                      class="w-full h-10 border border-gray-300 rounded-lg cursor-pointer"
                    />
                  </div>
                </div>
              </div>

              <!-- Border Controls -->
              <div v-if="selectedElement.type === 'rectangle' || selectedElement.type === 'circle'">
                <label class="block text-sm font-medium text-gray-700 mb-2">Border</label>
                <div class="grid grid-cols-2 gap-2">
                  <div>
                    <label class="block text-xs text-gray-500 mb-1">Color</label>
                    <input 
                      v-model="selectedElement.borderColor" 
                      type="color" 
                      class="w-full h-10 border border-gray-300 rounded-lg cursor-pointer"
                    />
                  </div>
                  <div>
                    <label class="block text-xs text-gray-500 mb-1">Width</label>
                    <input 
                      v-model.number="selectedElement.borderWidth" 
                      type="number" 
                      min="0"
                      max="20"
                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                  </div>
                </div>
              </div>

              <!-- Rotation -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rotation</label>
                <input 
                  v-model.number="selectedElement.rotation" 
                  type="range" 
                  min="0" 
                  max="360" 
                  class="w-full"
                />
                <div class="text-center text-sm text-gray-500 mt-1">{{ selectedElement.rotation }}°</div>
              </div>

              <!-- Actions -->
              <div class="pt-4 border-t border-gray-200">
                <div class="flex gap-2">
                  <button 
                    @click="duplicateElement(selectedElement)"
                    class="flex-1 px-3 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                  >
                    Duplicate
                  </button>
                  <button 
                    @click="deleteElement(selectedElement.id)"
                    class="flex-1 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- No Element Selected -->
          <div v-if="activeTab === 'properties' && !selectedElement" class="text-center py-8">
            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
              <DocumentTextIcon class="w-6 h-6 text-gray-400" />
            </div>
            <p class="text-sm text-gray-500">Select an element to edit its properties</p>
          </div>
        </div>
      </div>

      <!-- Main Canvas Area -->
      <div class="flex-1 bg-gray-100 flex flex-col">
        <!-- Canvas Toolbar -->
        <div class="bg-white border-b border-gray-200 px-6 py-3">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <!-- Zoom Controls -->
              <div class="flex items-center gap-2">
                <button 
                  @click="zoom = Math.max(25, zoom - 25)"
                  class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                  title="Zoom Out"
                >
                  <MagnifyingGlassMinusIcon class="w-4 h-4" />
                </button>
                <span class="text-sm font-medium text-gray-700 min-w-[60px] text-center">{{ zoom }}%</span>
                <button 
                  @click="zoom = Math.min(400, zoom + 25)"
                  class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                  title="Zoom In"
                >
                  <MagnifyingGlassPlusIcon class="w-4 h-4" />
                </button>
              </div>

              <div class="w-px h-6 bg-gray-300"></div>

              <!-- Page Info -->
              <div class="text-sm text-gray-600">
                Page {{ currentPageIndex + 1 }} of {{ pages.length }}
              </div>
            </div>

            <!-- Canvas Size -->
            <div class="text-sm text-gray-500">
              {{ currentPage?.width || 800 }} × {{ currentPage?.height || 600 }}
            </div>
          </div>
        </div>

        <!-- Canvas Container -->
        <div class="flex-1 bg-gray-100 overflow-auto" style="height: calc(100vh - 120px);">
          <!-- Canvas without Ruler -->
          <div v-if="!showRuler" class="flex justify-center items-start p-8">
            <div 
              class="relative bg-white shadow-lg canvas-container"
              :style="{
                width: `${(currentPage?.width || 800) * (zoom / 100)}px`,
                height: `${(currentPage?.height || 600) * (zoom / 100)}px`,
                backgroundImage: currentPage?.imageUrl ? `url(${currentPage.imageUrl})` : 'none',
                backgroundSize: 'contain',
                backgroundRepeat: 'no-repeat',
                backgroundPosition: 'center'
              }"
              @mousedown="isDrawing ? handleDrawingStart($event) : handleCanvasClick($event)"
            >
              <!-- Loading Indicator -->
              <div v-if="isLoadingPdf" class="absolute inset-0 bg-white/80 flex items-center justify-center z-50">
                <div class="text-center">
                  <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                  <p class="text-gray-600 font-medium">Loading PDF...</p>
                </div>
              </div>
              <!-- Grid Overlay -->
              <div 
                v-if="showGrid" 
                class="absolute inset-0 pointer-events-none"
                :style="{
                  backgroundImage: `linear-gradient(rgba(0,0,0,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.1) 1px, transparent 1px)`,
                  backgroundSize: '20px 20px'
                }"
              ></div>

              <!-- Extracted Text Overlay -->
              <div v-if="showTextOverlay && !isLoadingPdf" class="absolute inset-0 pointer-events-auto" :style="{ zIndex: 9999 }">
                <div
                  v-for="run in (textRunsByPage[currentPageIndex] || [])"
                  :key="run.id"
                  class="absolute cursor-text"
                  :style="{
                    left: `${run.x * (zoom / 100)}px`,
                    top: `${(run.y - run.height) * (zoom / 100)}px`,
                    width: `${run.width * (zoom / 100)}px`,
                    height: `${run.height * (zoom / 100)}px`,
                    backgroundColor: 'rgba(255, 255, 0, 0.2)'
                  }"
                  @click.stop="editTextRun(run)"
                  title="Click to edit text"
                ></div>
              </div>

              <!-- Elements -->
              <div 
                v-for="element in currentPage?.elements || []" 
                :key="element.id"
                class="absolute cursor-move"
                :style="{
                  left: `${element.x}px`,
                  top: `${element.y}px`,
                  width: `${element.width}px`,
                  height: `${element.height}px`,
                  zIndex: element.zIndex || 1,
                  transform: `rotate(${element.rotation || 0}deg)`,
                  border: selectedElement?.id === element.id ? '2px solid #3b82f6' : 'none'
                }"
                @mousedown="startDrag($event, element)"
                @click.stop="selectElement(element)"
              >
                <!-- Resize Handles -->
                <div 
                  v-if="selectedElement?.id === element.id"
                  class="absolute inset-0 pointer-events-none"
                >
                  <!-- Corner handles -->
                  <div 
                    v-for="handle in ['nw', 'ne', 'sw', 'se']" 
                    :key="handle"
                    class="absolute w-3 h-3 bg-blue-500 border-2 border-white rounded-full cursor-pointer pointer-events-auto"
                    :class="{
                      'top-0 left-0': handle === 'nw',
                      'top-0 right-0': handle === 'ne',
                      'bottom-0 left-0': handle === 'sw',
                      'bottom-0 right-0': handle === 'se'
                    }"
                    @mousedown.stop="startResize($event, element, handle)"
                  ></div>
                  
                  <!-- Edge handles -->
                  <div 
                    v-for="handle in ['n', 's', 'e', 'w']" 
                    :key="handle"
                    class="absolute w-3 h-3 bg-blue-500 border-2 border-white rounded-full cursor-pointer pointer-events-auto"
                    :class="{
                      'top-1/2 left-0 transform -translate-y-1/2': handle === 'w',
                      'top-1/2 right-0 transform -translate-y-1/2': handle === 'e',
                      'left-1/2 top-0 transform -translate-x-1/2': handle === 'n',
                      'left-1/2 bottom-0 transform -translate-x-1/2': handle === 's'
                    }"
                    @mousedown.stop="startResize($event, element, handle)"
                  ></div>
                  
                  <!-- Rotate handle -->
                  <div 
                    class="absolute w-3 h-3 bg-green-500 border-2 border-white rounded-full cursor-pointer pointer-events-auto"
                    style="top: -20px; left: 50%; transform: translateX(-50%);"
                    @mousedown.stop="startRotate($event, element)"
                  ></div>
                </div>
                
                <!-- Element Content -->
                <div class="w-full h-full">
                  <!-- Text Element -->
                  <div v-if="element.type === 'text'" class="w-full h-full flex items-center justify-center" @dblclick.stop="startTextEditing(element)">
                    <div 
                      v-if="!(isEditingText && editingTextId === element.id)"
                      class="w-full h-full flex items-center justify-center p-2"
                      :style="{
                        color: element.color || '#000000',
                        backgroundColor: element.backgroundColor || 'transparent',
                        fontSize: `${element.fontSize || 16}px`,
                        fontFamily: element.fontFamily || 'Arial',
                        fontWeight: element.fontWeight || 'normal',
                        fontStyle: element.fontStyle || 'normal',
                        textDecoration: element.textDecoration || 'none'
                      }"
                    >
                      {{ element.content || 'Text' }}
                    </div>
                    <textarea
                      v-else
                      ref="editingTextarea"
                      :value="element.content || ''"
                      @input="handleTextChange"
                      @blur="stopTextEditing"
                      @mousedown.stop
                      class="w-full h-full p-2 bg-transparent outline-none border-0 resize-none"
                      autofocus
                    ></textarea>
                  </div>
                  
                  <!-- Rectangle Element -->
                  <div v-else-if="element.type === 'rectangle'" class="w-full h-full">
                    <div 
                      class="w-full h-full"
                      :style="{
                        backgroundColor: element.backgroundColor || 'transparent',
                        border: element.borderWidth ? `${element.borderWidth}px solid ${element.borderColor || '#000000'}` : 'none'
                      }"
                    ></div>
                  </div>
                  
                  <!-- Circle Element -->
                  <div v-else-if="element.type === 'circle'" class="w-full h-full">
                    <div 
                      class="w-full h-full rounded-full"
                      :style="{
                        backgroundColor: element.backgroundColor || 'transparent',
                        border: element.borderWidth ? `${element.borderWidth}px solid ${element.borderColor || '#000000'}` : 'none'
                      }"
                    ></div>
                  </div>
                  
                  <!-- Image Element -->
                  <div v-else-if="element.type === 'image'" class="w-full h-full">
                    <img 
                      :src="element.imageUrl" 
                      :alt="element.content || 'Image'"
                      class="w-full h-full object-contain"
                      style="max-width: 100%; max-height: 100%;"
                    />
                  </div>
                  
                  <!-- Drawing Element -->
                  <div v-else-if="element.type === 'drawing'" class="w-full h-full">
                    <svg 
                      class="w-full h-full"
                      :viewBox="`0 0 ${element.width} ${element.height}`"
                      preserveAspectRatio="none"
                    >
                      <path 
                        :d="element.path" 
                        :stroke="element.color || '#000000'"
                        :stroke-width="element.thickness || 2"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      />
                    </svg>
                  </div>
                  
                  <!-- Signature Element -->
                  <div v-else-if="element.type === 'signature'" class="w-full h-full">
                    <svg 
                      class="w-full h-full"
                      :viewBox="`0 0 ${element.width} ${element.height}`"
                      preserveAspectRatio="none"
                    >
                      <g v-for="(stroke, strokeIndex) in element.paths" :key="strokeIndex">
                        <path 
                          :d="stroke.map((point, index) => 
                            index === 0 ? `M ${point.x} ${point.y}` : `L ${point.x} ${point.y}`
                          ).join(' ')"
                          :stroke="element.color || '#000000'"
                          :stroke-width="element.thickness || 2"
                          fill="none"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        />
                      </g>
                    </svg>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Canvas with Ruler -->
          <div v-if="showRuler" class="flex justify-center items-start p-8">
            <div 
              class="relative bg-white shadow-lg canvas-container"
              :style="{
                width: `${(currentPage?.width || 800) * (zoom / 100)}px`,
                height: `${(currentPage?.height || 600) * (zoom / 100)}px`,
                backgroundImage: currentPage?.imageUrl ? `url(${currentPage.imageUrl})` : 'none',
                backgroundSize: 'contain',
                backgroundRepeat: 'no-repeat',
                backgroundPosition: 'center'
              }"
              @mousedown="isDrawing ? handleDrawingStart($event) : handleCanvasClick($event)"
            >
              <!-- Loading Indicator -->
              <div v-if="isLoadingPdf" class="absolute inset-0 bg-white/80 flex items-center justify-center z-50">
                <div class="text-center">
                  <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                  <p class="text-gray-600 font-medium">Loading PDF...</p>
                </div>
              </div>
              <!-- Grid Overlay -->
              <div 
                v-if="showGrid" 
                class="absolute inset-0 pointer-events-none"
                :style="{
                  backgroundImage: `linear-gradient(rgba(0,0,0,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.1) 1px, transparent 1px)`,
                  backgroundSize: '20px 20px'
                }"
              ></div>

              <!-- Ruler Overlay -->
              <div class="absolute pointer-events-none">
                <!-- Horizontal Ruler -->
                <div class="absolute top-0 left-0 h-6 bg-gray-100 border-b border-gray-300" 
                     :style="{ 
                       width: `${(currentPage?.width || 800) * (zoom / 100)}px`,
                       top: '-24px'
                     }">
                  <!-- Inch markings -->
                  <div class="absolute top-0 left-0 right-0 h-6">
                    <div 
                      v-for="i in Math.ceil((currentPage?.width || 800) / 72)" 
                      :key="i"
                      class="absolute top-0 h-6"
                      :style="{ left: `${(i - 1) * 72 * (zoom / 100)}px` }"
                    >
                      <!-- Full inch mark -->
                      <div class="absolute top-0 left-0 w-px h-6 bg-gray-600"></div>
                      <!-- Half inch mark -->
                      <div class="absolute top-0 left-0 w-px h-4 bg-gray-400" :style="{ left: `${36 * (zoom / 100)}px` }"></div>
                      <!-- Quarter inch marks -->
                      <div class="absolute top-0 left-0 w-px h-3 bg-gray-300" :style="{ left: `${18 * (zoom / 100)}px` }"></div>
                      <div class="absolute top-0 left-0 w-px h-3 bg-gray-300" :style="{ left: `${54 * (zoom / 100)}px` }"></div>
                      <!-- Eighth inch marks -->
                      <div class="absolute top-0 left-0 w-px h-2 bg-gray-200" :style="{ left: `${9 * (zoom / 100)}px` }"></div>
                      <div class="absolute top-0 left-0 w-px h-2 bg-gray-200" :style="{ left: `${27 * (zoom / 100)}px` }"></div>
                      <div class="absolute top-0 left-0 w-px h-2 bg-gray-200" :style="{ left: `${45 * (zoom / 100)}px` }"></div>
                      <div class="absolute top-0 left-0 w-px h-2 bg-gray-200" :style="{ left: `${63 * (zoom / 100)}px` }"></div>
                      <!-- Inch label -->
                      <div class="absolute top-1 left-1 text-xs text-gray-600 font-medium">{{ i }}"</div>
                    </div>
                  </div>
                </div>
                <!-- Vertical Ruler -->
                <div class="absolute top-0 left-0 w-6 bg-gray-100 border-r border-gray-300" 
                     :style="{ 
                       height: `${(currentPage?.height || 600) * (zoom / 100)}px`,
                       left: '-24px'
                     }">
                  <!-- Inch markings -->
                  <div class="absolute top-0 left-0 bottom-0 w-6">
                    <div 
                      v-for="i in Math.ceil((currentPage?.height || 600) / 72)" 
                      :key="i"
                      class="absolute left-0 w-6"
                      :style="{ top: `${(i - 1) * 72 * (zoom / 100)}px` }"
                    >
                      <!-- Full inch mark -->
                      <div class="absolute top-0 left-0 w-6 h-px bg-gray-600"></div>
                      <!-- Half inch mark -->
                      <div class="absolute top-0 left-0 w-4 h-px bg-gray-400" :style="{ top: `${36 * (zoom / 100)}px` }"></div>
                      <!-- Quarter inch marks -->
                      <div class="absolute top-0 left-0 w-3 h-px bg-gray-300" :style="{ top: `${18 * (zoom / 100)}px` }"></div>
                      <div class="absolute top-0 left-0 w-3 h-px bg-gray-300" :style="{ top: `${54 * (zoom / 100)}px` }"></div>
                      <!-- Eighth inch marks -->
                      <div class="absolute top-0 left-0 w-2 h-px bg-gray-200" :style="{ top: `${9 * (zoom / 100)}px` }"></div>
                      <div class="absolute top-0 left-0 w-2 h-px bg-gray-200" :style="{ top: `${27 * (zoom / 100)}px` }"></div>
                      <div class="absolute top-0 left-0 w-2 h-px bg-gray-200" :style="{ top: `${45 * (zoom / 100)}px` }"></div>
                      <div class="absolute top-0 left-0 w-2 h-px bg-gray-200" :style="{ top: `${63 * (zoom / 100)}px` }"></div>
                      <!-- Inch label -->
                      <div class="absolute top-1 left-1 text-xs text-gray-600 font-medium transform rotate-90">{{ i }}"</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Elements -->
              <div 
                v-for="element in currentPage?.elements || []" 
                :key="element.id"
                class="absolute cursor-move"
                :style="{
                  left: `${element.x}px`,
                  top: `${element.y}px`,
                  width: `${element.width}px`,
                  height: `${element.height}px`,
                  zIndex: element.zIndex || 1,
                  transform: `rotate(${element.rotation || 0}deg)`,
                  border: selectedElement?.id === element.id ? '2px solid #3b82f6' : 'none'
                }"
                @mousedown="startDrag($event, element)"
                @click.stop="selectElement(element)"
              >
                <!-- Resize Handles -->
                <div 
                  v-if="selectedElement?.id === element.id"
                  class="absolute inset-0 pointer-events-none"
                >
                  <!-- Corner handles -->
                  <div 
                    v-for="handle in ['nw', 'ne', 'sw', 'se']" 
                    :key="handle"
                    class="absolute w-3 h-3 bg-blue-500 border-2 border-white rounded-full cursor-pointer pointer-events-auto"
                    :class="{
                      'top-0 left-0': handle === 'nw',
                      'top-0 right-0': handle === 'ne',
                      'bottom-0 left-0': handle === 'sw',
                      'bottom-0 right-0': handle === 'se'
                    }"
                    @mousedown.stop="startResize($event, element, handle)"
                  ></div>
                  
                  <!-- Edge handles -->
                  <div 
                    v-for="handle in ['n', 's', 'e', 'w']" 
                    :key="handle"
                    class="absolute w-3 h-3 bg-blue-500 border-2 border-white rounded-full cursor-pointer pointer-events-auto"
                    :class="{
                      'top-1/2 left-0 transform -translate-y-1/2': handle === 'w',
                      'top-1/2 right-0 transform -translate-y-1/2': handle === 'e',
                      'left-1/2 top-0 transform -translate-x-1/2': handle === 'n',
                      'left-1/2 bottom-0 transform -translate-x-1/2': handle === 's'
                    }"
                    @mousedown.stop="startResize($event, element, handle)"
                  ></div>
                  
                  <!-- Rotate handle -->
                  <div 
                    class="absolute w-3 h-3 bg-green-500 border-2 border-white rounded-full cursor-pointer pointer-events-auto"
                    style="top: -20px; left: 50%; transform: translateX(-50%);"
                    @mousedown.stop="startRotate($event, element)"
                  ></div>
                </div>
                
                <!-- Element Content -->
                <div class="w-full h-full">
                  <!-- Text Element -->
                  <div v-if="element.type === 'text'" class="w-full h-full flex items-center justify-center" @dblclick.stop="startTextEditing(element)">
                    <div 
                      v-if="!(isEditingText && editingTextId === element.id)"
                      class="w-full h-full flex items-center justify-center p-2"
                      :style="{
                        color: element.color || '#000000',
                        backgroundColor: element.backgroundColor || 'transparent',
                        fontSize: `${element.fontSize || 16}px`,
                        fontFamily: element.fontFamily || 'Arial',
                        fontWeight: element.fontWeight || 'normal',
                        fontStyle: element.fontStyle || 'normal',
                        textDecoration: element.textDecoration || 'none'
                      }"
                    >
                      {{ element.content || 'Text' }}
                    </div>
                    <textarea
                      v-else
                      ref="editingTextarea"
                      :value="element.content || ''"
                      @input="handleTextChange"
                      @blur="stopTextEditing"
                      @mousedown.stop
                      class="w-full h-full p-2 bg-transparent outline-none border-0 resize-none"
                      autofocus
                    ></textarea>
                  </div>
                  
                  <!-- Rectangle Element -->
                  <div v-else-if="element.type === 'rectangle'" class="w-full h-full">
                    <div 
                      class="w-full h-full"
                      :style="{
                        backgroundColor: element.backgroundColor || 'transparent',
                        border: element.borderWidth ? `${element.borderWidth}px solid ${element.borderColor || '#000000'}` : 'none'
                      }"
                    ></div>
                  </div>
                  
                  <!-- Circle Element -->
                  <div v-else-if="element.type === 'circle'" class="w-full h-full">
                    <div 
                      class="w-full h-full rounded-full"
                      :style="{
                        backgroundColor: element.backgroundColor || 'transparent',
                        border: element.borderWidth ? `${element.borderWidth}px solid ${element.borderColor || '#000000'}` : 'none'
                      }"
                    ></div>
                  </div>
                  
                  <!-- Image Element -->
                  <div v-else-if="element.type === 'image'" class="w-full h-full">
                    <img 
                      :src="element.imageUrl" 
                      :alt="element.content || 'Image'"
                      class="w-full h-full object-contain"
                      style="max-width: 100%; max-height: 100%;"
                    />
                  </div>
                  
                  <!-- Drawing Element -->
                  <div v-else-if="element.type === 'drawing'" class="w-full h-full">
                    <svg 
                      class="w-full h-full"
                      :viewBox="`0 0 ${element.width} ${element.height}`"
                      preserveAspectRatio="none"
                    >
                      <path 
                        :d="element.path" 
                        :stroke="element.color || '#000000'"
                        :stroke-width="element.thickness || 2"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      />
                    </svg>
                  </div>
                  
                  <!-- Signature Element -->
                  <div v-else-if="element.type === 'signature'" class="w-full h-full">
                    <svg 
                      class="w-full h-full"
                      :viewBox="`0 0 ${element.width} ${element.height}`"
                      preserveAspectRatio="none"
                    >
                      <g v-for="(stroke, strokeIndex) in element.paths" :key="strokeIndex">
                        <path 
                          :d="stroke.map((point, index) => 
                            index === 0 ? `M ${point.x} ${point.y}` : `L ${point.x} ${point.y}`
                          ).join(' ')"
                          :stroke="element.color || '#000000'"
                          :stroke-width="element.thickness || 2"
                          fill="none"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        />
                      </g>
                    </svg>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> 
  </div>
  <!-- Hidden Image Input -->
  <input 
    ref="imageInputRef"
    type="file" 
    accept="image/*"
    @change="handleSidebarImage"
    class="hidden"
  />



  <!-- Signature Modal -->
  <div v-if="showSignatureModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Create Signature</h3>
        <button 
          @click="closeSignatureModal"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-6">
        <!-- Signature Settings -->
        <div class="flex items-center gap-4 mb-4">
          <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">Color:</label>
            <input 
              v-model="signatureColor" 
              type="color" 
              class="w-12 h-8 border border-gray-300 rounded cursor-pointer"
            />
          </div>
          <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">Thickness:</label>
            <input 
              v-model.number="signatureThickness" 
              type="range" 
              min="1"
              max="10"
              class="w-20 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
            />
            <span class="text-sm text-gray-600 w-8">{{ signatureThickness }}px</span>
          </div>
          <button 
            @click="clearSignature"
            class="px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
          >
            Clear
          </button>
        </div>

        <!-- Signature Canvas -->
        <div class="border-2 border-gray-300 rounded-lg bg-white">
          <canvas
            ref="signatureCanvas"
            width="600"
            height="200"
            class="w-full h-48 cursor-crosshair"
            @mousedown="startSignatureDrawing"
            @mousemove="handleSignatureDrawing"
            @mouseup="stopSignatureDrawing"
            @mouseleave="stopSignatureDrawing"
          ></canvas>
        </div>

        <!-- Instructions -->
        <div class="mt-3 text-sm text-gray-600 text-center">
          Draw your signature above. Click and drag to create your signature.
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200">
        <button 
          @click="closeSignatureModal"
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200"
        >
          Cancel
        </button>
        <button 
          @click="addSignatureToPage"
          :disabled="currentSignaturePath.length === 0"
          :class="[
            'px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200',
            currentSignaturePath.length === 0 
              ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
              : 'bg-blue-600 text-white hover:bg-blue-700'
          ]"
        >
          Add Signature
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, onUnmounted, nextTick } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { PDFDocument, rgb, StandardFonts } from 'pdf-lib'
import * as pdfjsLib from 'pdfjs-dist';

// Initialize PDF.js worker to match the installed library version
try {
  const workerUrl = new URL('pdfjs-dist/build/pdf.worker.min.mjs', import.meta.url)
  pdfjsLib.GlobalWorkerOptions.workerPort = new Worker(workerUrl, { type: 'module' })
} catch (e1) {
  try {
    const workerUrl = new URL('pdfjs-dist/build/pdf.worker.mjs', import.meta.url)
    pdfjsLib.GlobalWorkerOptions.workerPort = new Worker(workerUrl, { type: 'module' })
  } catch (e2) {
    // Final fallback to static path if bundling is unavailable
    pdfjsLib.GlobalWorkerOptions.workerSrc = (import.meta.env.BASE_URL || '/') + 'pdf.worker.js'
  }
}

import {
  ArrowLeftIcon,
  ArrowUturnLeftIcon,
  ArrowUturnRightIcon,
  ViewColumnsIcon,
  Square3Stack3DIcon,
  ArrowDownTrayIcon,
  DocumentTextIcon,
  PhotoIcon,
  Square2StackIcon,
  MagnifyingGlassMinusIcon,
  MagnifyingGlassPlusIcon,
  PlusIcon,
  TrashIcon,
  DocumentIcon,
  Squares2X2Icon,
  CircleStackIcon,
  PencilIcon
} from '@heroicons/vue/24/outline'

const router = useRouter()
const route = useRoute()

// Props
const mode = computed(() => route.query.mode || 'create')
const uploadedFile = computed(() => {
  // Try to get file from localStorage first
  const pdfData = localStorage.getItem('uploadedPdfData')
  const pdfName = localStorage.getItem('uploadedPdfName')
  const pdfSize = localStorage.getItem('uploadedPdfSize')
  
  if (pdfData && pdfName && pdfSize) {
    // Convert data URL back to File object
    const base64Data = pdfData.split(',')[1]
    const byteCharacters = atob(base64Data)
    const byteNumbers = new Array(byteCharacters.length)
    for (let i = 0; i < byteCharacters.length; i++) {
      byteNumbers[i] = byteCharacters.charCodeAt(i)
    }
    const byteArray = new Uint8Array(byteNumbers)
    const file = new File([byteArray], pdfName, { type: 'application/pdf' })
    
    console.log('Retrieved file from localStorage:', file.name, file.size)
    return file
  }
  
  // Fallback to router state
  console.log('Route state:', route.state)
  console.log('Route query:', route.query)
  return route.state?.uploadedFile || null
})

// PDF loading state
const pdfDocument = ref(null)
const pdfPages = ref([])
const isLoadingPdf = ref(false)
const showTextOverlay = ref(false)
const textRunsByPage = ref({})
const isEditingPdfText = ref(false)
const editingRun = ref(null)

// Load PDF function
const loadPdfFile = async (file) => {
  try {
    console.log('Starting PDF load for file:', file.name, file.size)
    isLoadingPdf.value = true
    
    const arrayBuffer = await file.arrayBuffer()
    console.log('File read as ArrayBuffer, size:', arrayBuffer.byteLength)
    
    // Load PDF with pdf-lib for export functionality
    const pdfDoc = await PDFDocument.load(arrayBuffer)
    pdfDocument.value = pdfDoc
    console.log('PDF-lib document loaded for export, pages:', pdfDoc.getPageCount())
    
    const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise
    console.log('PDF.js document loaded, pages:', pdf.numPages)
    
    const newPages = []
    const extractedTextRuns = {}
    
    for (let i = 0; i < pdf.numPages; i++) {
      const page = await pdf.getPage(i + 1)
      const viewport = page.getViewport({ scale: 1.0 })
      
      const canvas = document.createElement('canvas')
      const context = canvas.getContext('2d')
      canvas.width = viewport.width
      canvas.height = viewport.height
      
      await page.render({
        canvasContext: context,
        viewport: viewport,
      }).promise
      
      const imageUrl = canvas.toDataURL()
      
      console.log(`Successfully rendered PDF page ${i + 1} to image, size:`, canvas.width, 'x', canvas.height)
      console.log(`PDF page ${i + 1} dimensions in inches:`, (canvas.width / 72).toFixed(2), 'x', (canvas.height / 72).toFixed(2), 'inches')
      
      newPages.push({
        id: (i + 1).toString(),
        elements: [],
        imageUrl: imageUrl,
        width: viewport.width,
        height: viewport.height,
        pdfPageIndex: i,
      })

      // Extract text runs for overlay (using pdf.js)
      const textContent = await page.getTextContent()
      const pageRuns = textContent.items.map((item, idx) => {
        const tx = pdfjsLib.Util.transform(viewport.transform, item.transform)
        const x = tx[4]
        const y = tx[5]
        const fontSize = Math.hypot(tx[0], tx[1])
        const width = item.width
        const height = fontSize
        return {
          id: `${i + 1}-${idx}`,
          str: item.str,
          x,
          y,
          width,
          height,
          fontSize,
        }
      })
      extractedTextRuns[i] = pageRuns
    }
    
    console.log('Created pages array:', newPages.length, 'pages')
    pages.value = newPages
    pdfPages.value = newPages
    textRunsByPage.value = extractedTextRuns
    
    // Ensure currentPageIndex is valid
    if (currentPageIndex.value >= newPages.length) {
      currentPageIndex.value = 0
      console.log('Reset currentPageIndex to 0')
    }
    
    // Update page dimensions to match PDF
    if (newPages.length > 0) {
      pageWidth.value = newPages[0].width
      pageHeight.value = newPages[0].height
      // Set zoom to 100% for accurate measurements
      zoom.value = 100
      console.log('Updated page dimensions:', pageWidth.value, 'x', pageHeight.value)
      console.log('Page dimensions in inches:', (pageWidth.value / 72).toFixed(2), 'x', (pageHeight.value / 72).toFixed(2), 'inches')
      console.log('Set zoom level to:', zoom.value)
      console.log('Current page index after PDF load:', currentPageIndex.value)
      console.log('Current page after PDF load:', pages.value[currentPageIndex.value])
    }
    
    addToHistory(newPages)
    isLoadingPdf.value = false
    console.log('PDF loading completed successfully')
    
    // Clear localStorage after successful load
    localStorage.removeItem('uploadedPdfData')
    localStorage.removeItem('uploadedPdfName')
    localStorage.removeItem('uploadedPdfSize')
    
  } catch (error) {
    console.error('Error loading PDF:', error)
    isLoadingPdf.value = false
    alert('Error loading PDF. Please try again.')
  }
}

// Watch for changes in the uploaded file
watch(uploadedFile, (newFile) => {
  console.log('Uploaded file changed:', newFile)
  if (newFile && mode.value === 'edit') {
    console.log('Loading PDF file:', newFile.name, newFile.size)
    loadPdfFile(newFile)
  }
})

// Watch for mode changes
watch(mode, (newMode) => {
  console.log('Mode changed:', newMode)
  if (newMode === 'edit' && uploadedFile.value) {
    console.log('Loading PDF file from mode change:', uploadedFile.value.name, uploadedFile.value.size)
    loadPdfFile(uploadedFile.value)
  }
})

// Reactive state
const currentPageIndex = ref(0)
const selectedElement = ref(null)
const zoom = ref(100)
const showGrid = ref(false)
const showRuler = ref(false)
const history = ref([])
const historyIndex = ref(-1)
const activeTab = ref('elements')
const isEditingText = ref(false)
const editingTextId = ref(null)
const isDragging = ref(false)
const isResizing = ref(false)
const isRotating = ref(false)
const dragStart = ref({ x: 0, y: 0 })
const dragElement = ref(null)
const resizeHandle = ref(null)
const rotateStart = ref({ x: 0, y: 0, centerX: 0, y: 0, angle: 0 })
const exportMode = ref('current') // New state for export mode

// Copy and paste functionality
const clipboard = ref(null)

// Drawing functionality
const isDrawing = ref(false)
const drawingColor = ref('#000000')
const drawingThickness = ref(2)
const currentDrawingPath = ref([])
const drawingElement = ref(null)

// Signature functionality
const showSignatureModal = ref(false)
const signatureColor = ref('#000000')
const signatureThickness = ref(2)
const currentSignaturePath = ref([])
const signatureCanvas = ref(null)
const isDrawingSignature = ref(false)
const allSignatureStrokes = ref([])

// Page size and orientation management
const selectedPageSize = ref('a4')
const pageOrientation = ref('portrait')
const pageWidth = ref(595) // A4 width in points
const pageHeight = ref(842) // A4 height in points

// Page size definitions (in points - 1 point = 1/72 inch)
const pageSizes = {
  a4: { width: 595, height: 842 },
  letter: { width: 612, height: 792 },
  legal: { width: 612, height: 1008 },
  a3: { width: 842, height: 1191 },
  a5: { width: 420, height: 595 },
  tabloid: { width: 792, height: 1224 }
}

// --- IMAGE BUTTON LOGIC ---
const imageInputRef = ref(null)

const triggerImageInput = () => {
  if (imageInputRef.value) imageInputRef.value.value = '' // reset
  imageInputRef.value?.click()
}

const handleSidebarImage = async (event) => {
  const file = event.target.files[0]
  if (file && file.type.startsWith('image/')) {
    try {
      // Read the file as ArrayBuffer for PDF export
      const arrayBuffer = await file.arrayBuffer()
      const url = URL.createObjectURL(file)
      
      const newElement = {
        id: Date.now().toString(),
        type: 'image',
        x: 100,
        y: 100,
        width: 200,
        height: 150,
        content: file.name,
        imageUrl: url,
        imageData: arrayBuffer, // Store the original file data
        imageType: file.type, // Store the MIME type
        rotation: 0,
        zIndex: Math.max(...pages.value[currentPageIndex.value].elements.map(e => e.zIndex), 0) + 1
      }
      const updatedPages = [...pages.value]
      updatedPages[currentPageIndex.value].elements.push(newElement)
      pages.value = updatedPages
      addToHistory(updatedPages)
      selectedElement.value = newElement
    } catch (error) {
      console.error('Error processing image:', error)
      alert('Error processing image. Please try again.')
    }
  }
}

// --- CIRCLE CREATION LOGIC ---
const addElement = (type) => {
  if (pages.value.length === 0) return
  if (type === 'image') {
    triggerImageInput()
    return
  }
  if (type === 'drawing') {
    startDrawing()
    return
  }
  let width = 100, height = 100
  if (type === 'text') { width = 200; height = 50 }
  if (type === 'circle') { width = 100; height = 100 }
  const newElement = {
    id: Date.now().toString(),
    type,
    x: 100,
    y: 100,
    width,
    height,
    content: type === 'text' ? 'Double click to edit' : undefined,
    fontSize: type === 'text' ? 16 : undefined,
    fontFamily: type === 'text' ? 'Arial' : undefined,
    fontWeight: type === 'text' ? 'normal' : undefined,
    fontStyle: type === 'text' ? 'normal' : undefined,
    textDecoration: type === 'text' ? 'none' : undefined,
    color: '#000000',
    backgroundColor: type === 'rectangle' || type === 'circle' ? '#ffffff' : undefined,
    borderColor: '#000000',
    borderWidth: 0,
    rotation: 0,
    zIndex: Math.max(...pages.value[currentPageIndex.value].elements.map(e => e.zIndex), 0) + 1
  }
  const updatedPages = [...pages.value]
  updatedPages[currentPageIndex.value].elements.push(newElement)
  pages.value = updatedPages
  addToHistory(updatedPages)
  selectedElement.value = newElement
}

// Computed
const currentPage = computed(() => {
  const page = pages.value[currentPageIndex.value]
  console.log('Current page computed:', {
    pagesLength: pages.value.length,
    currentPageIndex: currentPageIndex.value,
    page: page,
    pageElements: page?.elements?.length || 0
  })
  return page
})
const sortedElements = computed(() => {
  if (!currentPage.value) return []
  return [...currentPage.value.elements].sort((a, b) => a.zIndex - b.zIndex)
})

// Methods
const addToHistory = (newPages) => {
  const newHistory = history.value.slice(0, historyIndex.value + 1)
  newHistory.push(JSON.parse(JSON.stringify(newPages)))
  history.value = newHistory
  historyIndex.value = newHistory.length - 1
}

const undo = () => {
  if (historyIndex.value > 0) {
    historyIndex.value--
    pages.value = JSON.parse(JSON.stringify(history.value[historyIndex.value]))
  }
}

const redo = () => {
  if (historyIndex.value < history.value.length - 1) {
    historyIndex.value++
    pages.value = JSON.parse(JSON.stringify(history.value[historyIndex.value]))
  }
}

const updateElement = (id, updates) => {
  const updatedPages = [...pages.value]
  const pageIndex = currentPageIndex.value
  const elementIndex = updatedPages[pageIndex].elements.findIndex(el => el.id === id)
  
  if (elementIndex !== -1) {
    updatedPages[pageIndex].elements[elementIndex] = { ...updatedPages[pageIndex].elements[elementIndex], ...updates }
    pages.value = updatedPages
    addToHistory(updatedPages)
  }
}

const sendToFront = () => {
  if (!selectedElement.value) return
  
  const updatedPages = [...pages.value]
  const pageIndex = currentPageIndex.value
  const elements = updatedPages[pageIndex].elements
  
  // Find the highest z-index
  const maxZIndex = Math.max(...elements.map(el => el.zIndex || 0))
  
  // Update the selected element's z-index
  const elementIndex = elements.findIndex(el => el.id === selectedElement.value.id)
  if (elementIndex !== -1) {
    elements[elementIndex].zIndex = maxZIndex + 1
    pages.value = updatedPages
    addToHistory(updatedPages)
  }
}

const sendToBack = () => {
  if (!selectedElement.value) return
  
  const updatedPages = [...pages.value]
  const pageIndex = currentPageIndex.value
  const elements = updatedPages[pageIndex].elements
  
  // Find the lowest z-index
  const minZIndex = Math.min(...elements.map(el => el.zIndex || 0))
  
  // Update the selected element's z-index
  const elementIndex = elements.findIndex(el => el.id === selectedElement.value.id)
  if (elementIndex !== -1) {
    elements[elementIndex].zIndex = minZIndex - 1
    pages.value = updatedPages
    addToHistory(updatedPages)
  }
}

const deleteSelectedElement = () => {
  if (!selectedElement.value) return
  
  const updatedPages = [...pages.value]
  const pageIndex = currentPageIndex.value
  const elements = updatedPages[pageIndex].elements
  
  // Remove the selected element
  const filteredElements = elements.filter(el => el.id !== selectedElement.value.id)
  updatedPages[pageIndex].elements = filteredElements
  
  pages.value = updatedPages
  addToHistory(updatedPages)
  selectedElement.value = null
}

const deleteElement = (elementId) => {
  const updatedPages = [...pages.value]
  const elementIndex = updatedPages[currentPageIndex.value].elements.findIndex(e => e.id === elementId)
  if (elementIndex !== -1) {
    updatedPages[currentPageIndex.value].elements.splice(elementIndex, 1)
    pages.value = updatedPages
    addToHistory(updatedPages)
    selectedElement.value = null
  }
}

const copyElement = () => {
  if (selectedElement.value) {
    // Create a deep copy manually to handle ArrayBuffer properly
    const elementCopy = { ...selectedElement.value }
    
    // Copy all properties except id (which will be regenerated)
    delete elementCopy.id
    
    // Special handling for images to preserve imageData
    if (selectedElement.value.type === 'image') {
      // For images, we need to preserve the ArrayBuffer data and other image properties
      if (selectedElement.value.imageData) {
        elementCopy.imageData = selectedElement.value.imageData
        elementCopy.imageType = selectedElement.value.imageType
        console.log('Image copied with data, size:', elementCopy.imageData?.byteLength)
      }
      
      // Also preserve the blob URL for display purposes
      if (selectedElement.value.imageUrl) {
        elementCopy.imageUrl = selectedElement.value.imageUrl
      }
      
      console.log('Image copied successfully with all properties')
    }
    
    clipboard.value = elementCopy
    console.log('Element copied to clipboard:', elementCopy)
  }
}

const pasteElement = () => {
  if (clipboard.value) {
    const newElement = {
      ...clipboard.value,
      id: Date.now().toString(),
      x: clipboard.value.x + 20,
      y: clipboard.value.y + 20,
      zIndex: Math.max(...pages.value[currentPageIndex.value].elements.map(e => e.zIndex), 0) + 1
    }
    
    // Special handling for pasted images
    if (clipboard.value.type === 'image') {
      console.log('Pasting image with data:', {
        hasImageData: !!newElement.imageData,
        imageDataSize: newElement.imageData?.byteLength,
        hasImageType: !!newElement.imageType,
        hasImageUrl: !!newElement.imageUrl
      })
    }
    
    const updatedPages = [...pages.value]
    updatedPages[currentPageIndex.value].elements.push(newElement)
    pages.value = updatedPages
    addToHistory(updatedPages)
    selectedElement.value = newElement
    
    console.log('Element pasted from clipboard:', newElement)
  }
}

// PDF text editing (overlay + regenerate page content)
const editTextRun = (run) => {
  isEditingPdfText.value = true
  editingRun.value = { ...run }
  // Add a temporary text element positioned over the run for editing
  const newElement = {
    id: `edit-${Date.now()}`,
    type: 'text',
    x: run.x,
    y: run.y - run.height,
    width: run.width,
    height: run.height,
    content: run.str,
    fontSize: run.fontSize || 16,
    fontFamily: 'Helvetica',
    fontWeight: 'normal',
    fontStyle: 'normal',
    textDecoration: 'none',
    color: '#000000',
    rotation: 0,
    zIndex: Math.max(...pages.value[currentPageIndex.value].elements.map(e => e.zIndex), 0) + 10
  }
  const updatedPages = [...pages.value]
  updatedPages[currentPageIndex.value].elements.push(newElement)
  pages.value = updatedPages
  addToHistory(updatedPages)
  selectedElement.value = newElement
  // Enable existing inline editing UX
  startTextEditing(newElement)
}

const duplicateElement = (element) => {
  const newElement = {
    ...element,
    id: Date.now().toString(),
    x: element.x + 20,
    y: element.y + 20,
    zIndex: Math.max(...pages.value[currentPageIndex.value].elements.map(e => e.zIndex), 0) + 1
  }
  
  const updatedPages = [...pages.value]
  updatedPages[currentPageIndex.value].elements.push(newElement)
  pages.value = updatedPages
  addToHistory(updatedPages)
  selectedElement.value = newElement
}

const addPage = () => {
  const newPage = {
    id: Date.now().toString(),
    elements: [],
    width: 595,
    height: 842
  }
  const updatedPages = [...pages.value, newPage]
  pages.value = updatedPages
  addToHistory(updatedPages)
  currentPageIndex.value = updatedPages.length - 1
}

const deletePage = (pageIndex) => {
  if (pages.value.length <= 1) return

  const updatedPages = pages.value.filter((_, index) => index !== pageIndex)
  pages.value = updatedPages
  addToHistory(updatedPages)

  if (currentPageIndex.value >= updatedPages.length) {
    currentPageIndex.value = updatedPages.length - 1
  }
}

const duplicatePage = (pageIndex) => {
  const newPage = {
    ...pages.value[pageIndex],
    id: Date.now().toString(),
    elements: [...pages.value[pageIndex].elements]
  }
  const updatedPages = [...pages.value]
  updatedPages.splice(pageIndex + 1, 0, newPage)
  pages.value = updatedPages
  addToHistory(updatedPages)
  currentPageIndex.value = updatedPages.length - 1
}

const setCurrentPageIndex = (index) => {
  currentPageIndex.value = index
}

const setSelectedElement = (element) => {
  selectedElement.value = element
}

const selectElement = (element) => {
  selectedElement.value = element
  
  // Auto-deactivate drawing mode when selecting other elements
  if (isDrawing.value && element.type !== 'drawing') {
    isDrawing.value = false
    currentDrawingPath.value = []
    drawingElement.value = null
  }
}

const setZoom = (newZoom) => {
  zoom.value = newZoom
}

const goBack = () => {
  router.push('/')
}

const exportToPDF = async () => {
  try {
    let pdfDoc
    
    // If we're in edit mode and have a loaded PDF, use it as the base
    if (mode.value === 'edit' && pdfDocument.value) {
      pdfDoc = await PDFDocument.load(await pdfDocument.value.save())
      
      // If exporting current page only, remove other pages
      if (exportMode.value === 'current') {
        const selectedPageIndex = currentPageIndex.value
        const totalPages = pdfDoc.getPageCount()
        
        // Remove pages from the end to avoid index shifting
        for (let i = totalPages - 1; i >= 0; i--) {
          if (i !== selectedPageIndex) {
            pdfDoc.removePage(i)
          }
        }
      }
    } else {
      // Create a new PDF document for create mode
      pdfDoc = await PDFDocument.create()
    }
    
    // Embed fonts for different font families
    const fonts = {
      // Helvetica family
      'Helvetica': {
        normal: await pdfDoc.embedFont(StandardFonts.Helvetica),
        bold: await pdfDoc.embedFont(StandardFonts.HelveticaBold),
        italic: await pdfDoc.embedFont(StandardFonts.HelveticaOblique),
        boldItalic: await pdfDoc.embedFont(StandardFonts.HelveticaBoldOblique)
      },
      // Times New Roman family
      'Times New Roman': {
        normal: await pdfDoc.embedFont(StandardFonts.TimesRoman),
        bold: await pdfDoc.embedFont(StandardFonts.TimesRomanBold),
        italic: await pdfDoc.embedFont(StandardFonts.TimesRomanItalic),
        boldItalic: await pdfDoc.embedFont(StandardFonts.TimesRomanBoldItalic)
      },
      // Courier family
      'Courier New': {
        normal: await pdfDoc.embedFont(StandardFonts.Courier),
        bold: await pdfDoc.embedFont(StandardFonts.CourierBold),
        italic: await pdfDoc.embedFont(StandardFonts.CourierOblique),
        boldItalic: await pdfDoc.embedFont(StandardFonts.CourierBoldOblique)
      },
      // Arial (use Helvetica as equivalent)
      'Arial': {
        normal: await pdfDoc.embedFont(StandardFonts.Helvetica),
        bold: await pdfDoc.embedFont(StandardFonts.HelveticaBold),
        italic: await pdfDoc.embedFont(StandardFonts.HelveticaOblique),
        boldItalic: await pdfDoc.embedFont(StandardFonts.HelveticaBoldOblique)
      }
    }
    
    // Default to Helvetica for unsupported fonts
    const getFont = (fontFamily, fontWeight, fontStyle) => {
      try {
        const family = fonts[fontFamily] || fonts['Helvetica']
        
        let selectedFont
        if (fontWeight === 'bold' && fontStyle === 'italic') {
          selectedFont = family.boldItalic
        } else if (fontWeight === 'bold') {
          selectedFont = family.bold
        } else if (fontStyle === 'italic') {
          selectedFont = family.italic
        } else {
          selectedFont = family.normal
        }
        
        console.log('Font selection:', {
          fontFamily: fontFamily,
          fontWeight: fontWeight,
          fontStyle: fontStyle,
          hasFont: !!selectedFont,
          fontType: fontWeight === 'bold' && fontStyle === 'italic' ? 'boldItalic' : 
                    fontWeight === 'bold' ? 'bold' : 
                    fontStyle === 'italic' ? 'italic' : 'normal'
        })
        
        return selectedFont
      } catch (error) {
        console.error('Error selecting font:', error)
        // Fallback to Helvetica normal
        return fonts['Helvetica'].normal
      }
    }
    
    // Determine which pages to export
    const pagesToExport = exportMode.value === 'current' 
      ? [currentPageIndex.value] 
      : Array.from({ length: pages.value.length }, (_, i) => i)
    
    // Process each page to export
    for (const pageIndex of pagesToExport) {
      const pageData = pages.value[pageIndex]
      if (!pageData) continue
      
      let page
      const pdfPageWidth = pageData.width || pageWidth.value
      const pdfPageHeight = pageData.height || pageHeight.value
      
      // If we're in edit mode and have a loaded PDF, get the existing page
      if (mode.value === 'edit' && pdfDocument.value && pageData.pdfPageIndex !== undefined) {
        page = pdfDoc.getPage(pageData.pdfPageIndex)
      } else {
        // Add a new page for create mode or if no existing page
        page = pdfDoc.addPage([pdfPageWidth, pdfPageHeight])
      }
      
      // Process each element on this page
      for (const element of pageData.elements || []) {
        const { x, y, width, height, content, fontSize, fontFamily, fontWeight, fontStyle, textDecoration, color, backgroundColor, borderColor, borderWidth, rotation, type, imageUrl } = element
        
        // Convert color from hex to RGB
        const hexToRgb = (hex) => {
          if (!hex) return { r: 0, g: 0, b: 0 }
          const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex)
          return result ? {
            r: parseInt(result[1], 16) / 255,
            g: parseInt(result[2], 16) / 255,
            b: parseInt(result[3], 16) / 255
          } : { r: 0, g: 0, b: 0 }
        }
        
        const textColor = hexToRgb(color || '#000000')
        const bgColor = backgroundColor ? hexToRgb(backgroundColor) : null
        const borderRgb = borderColor ? hexToRgb(borderColor) : hexToRgb('#000000')
        
        // Note: Rotation is handled by the rotate parameter in each drawing function
        
        if (type === 'text' && content) {
          // If showTextOverlay is on and this text overlaps an existing run, draw a white cover first
          if (showTextOverlay.value && textRunsByPage.value && textRunsByPage.value[pageIndex]) {
            const overlaps = textRunsByPage.value[pageIndex].some(run => {
              const rx = run.x, ry = pdfPageHeight - run.y
              return !(x + width < rx || x > rx + run.width || (pdfPageHeight - y - height) + height < ry || (pdfPageHeight - y - height) > ry + run.height)
            })
            if (overlaps) {
              page.drawRectangle({
                x: x,
                y: pdfPageHeight - y - height,
                width: width,
                height: height,
                color: rgb(1, 1, 1)
              })
            }
          }
          // Select appropriate font based on family and style
          const selectedFont = getFont(fontFamily || 'Arial', fontWeight || 'normal', fontStyle || 'normal')
          
          console.log('Text rendering:', {
            content: content,
            fontFamily: fontFamily || 'Arial',
            fontWeight: fontWeight || 'normal',
            fontStyle: fontStyle || 'normal',
            fontSize: fontSize || 16,
            textDecoration: textDecoration || 'none'
          })
          
          // Calculate text position
          const textSize = fontSize || 16
          const textY = pdfPageHeight - y - height + textSize
          
          // Apply text decoration (underline)
          if (textDecoration === 'underline') {
            const textWidth = selectedFont.widthOfTextAtSize(content, textSize)
            page.drawLine({
              start: { x: x, y: textY - 2 },
              end: { x: x + textWidth, y: textY - 2 },
              thickness: 1,
              color: rgb(textColor.r, textColor.g, textColor.b)
            })
          }
          
          // Draw the text with rotation
          page.drawText(content, {
            x: x,
            y: textY,
            size: textSize,
            font: selectedFont,
            color: rgb(textColor.r, textColor.g, textColor.b),
            rotate: { angle: rotation || 0, type: 'degrees' }
          })
          
        } else if (type === 'rectangle') {
          // Debug: Log the element dimensions
          console.log('Rectangle element:', { x, y, width, height, type, rotation })
          
          // Draw rectangle background
          if (bgColor) {
            page.drawRectangle({
              x: x,
              y: pdfPageHeight - y - height,
              width: width,
              height: height,
              color: rgb(bgColor.r, bgColor.g, bgColor.b),
              rotate: { angle: rotation || 0, type: 'degrees' }
            })
          }
          
          // Draw solid border only
          if (borderWidth && borderWidth > 0) {
            page.drawRectangle({
              x: x,
              y: pdfPageHeight - y - height,
              width: width,
              height: height,
              borderColor: rgb(borderRgb.r, borderRgb.g, borderRgb.b),
              borderWidth: borderWidth,
              rotate: { angle: rotation || 0, type: 'degrees' }
            })
          }
          
        } else if (type === 'circle') {
          // Debug: Log the element dimensions
          console.log('Circle element:', { x, y, width, height, type, rotation })
          
          // Draw circle with exact size matching - use the actual width from element
          const centerX = x + width / 2;
          const centerY = pdfPageHeight - y - height / 2;
          const radius = Math.min(width, height) / 2; // Calculate the radius
          
          // Adjust radius for border width (same as editor)
          const adjustedRadius = radius - (borderWidth || 0) / 2;
          
          console.log('Circle rendering:', {
            originalRadius: radius,
            borderWidth: borderWidth || 0,
            adjustedRadius: adjustedRadius,
            center: { x: centerX, y: centerY },
            hasBackground: !!bgColor,
            hasBorder: !!(borderWidth && borderWidth > 0)
          });
          
          // Draw circle with background and border
          page.drawCircle({
            x: centerX,
            y: centerY,
            size: adjustedRadius > 0 ? adjustedRadius : 0,
            color: bgColor ? rgb(bgColor.r, bgColor.g, bgColor.b) : undefined,
            borderColor: borderWidth && borderWidth > 0 ? rgb(borderRgb.r, borderRgb.g, borderRgb.b) : undefined,
            borderWidth: borderWidth || 0,
            rotate: { angle: rotation || 0, type: 'degrees' }
          });
          
        } else if (type === 'image' && imageUrl) {
          try {
            console.log('Processing image element:', { imageUrl, hasImageData: !!element.imageData, imageType: element.imageType })
            
            // Use stored image data if available, otherwise fetch from URL
            let imageBytes
            
            if (element.imageData) {
              // Use the stored image data (preferred method)
              imageBytes = element.imageData
              console.log('Using stored image data, size:', imageBytes.byteLength)
            } else if (imageUrl.startsWith('blob:')) {
              // For blob URLs, we need to fetch the blob data
              console.log('Fetching blob URL:', imageUrl)
              const response = await fetch(imageUrl)
              if (!response.ok) {
                throw new Error('Failed to fetch blob URL')
              }
              imageBytes = await response.arrayBuffer()
              console.log('Fetched blob data, size:', imageBytes.byteLength)
            } else if (imageUrl.startsWith('data:')) {
              // Handle data URLs
              console.log('Processing data URL')
              const base64Data = imageUrl.split(',')[1]
              imageBytes = Uint8Array.from(atob(base64Data), c => c.charCodeAt(0))
              console.log('Processed data URL, size:', imageBytes.byteLength)
            } else {
              // Handle external URLs
              console.log('Fetching external URL:', imageUrl)
              const response = await fetch(imageUrl)
              if (!response.ok) {
                throw new Error('Failed to fetch image')
              }
              imageBytes = await response.arrayBuffer()
              console.log('Fetched external data, size:', imageBytes.byteLength)
            }
            
            // Determine image format and embed
            let image
            try {
              console.log('Embedding image with type:', element.imageType)
              
              // Use stored MIME type if available, otherwise detect from data
              if (element.imageType) {
                // Use the stored MIME type for format detection
                if (element.imageType.includes('png')) {
                  console.log('Embedding as PNG')
                  image = await pdfDoc.embedPng(imageBytes)
                } else if (element.imageType.includes('jpeg') || element.imageType.includes('jpg')) {
                  console.log('Embedding as JPEG')
                  image = await pdfDoc.embedJpg(imageBytes)
                } else if (element.imageType.includes('gif')) {
                  // Convert GIF to PNG or handle appropriately
                  console.log('Converting GIF to PNG')
                  image = await pdfDoc.embedPng(imageBytes)
                } else if (element.imageType.includes('webp')) {
                  // Convert WebP to PNG or handle appropriately
                  console.log('Converting WebP to PNG')
                  image = await pdfDoc.embedPng(imageBytes)
                } else {
                  // Fallback: try PNG first, then JPEG
                  console.log('Unknown MIME type, trying PNG then JPEG')
                  try {
                    image = await pdfDoc.embedPng(imageBytes)
                  } catch (pngError) {
                    try {
                      image = await pdfDoc.embedJpg(imageBytes)
                    } catch (jpgError) {
                      throw new Error('Unsupported image format')
                    }
                  }
                }
              } else {
                // Fallback to signature detection
                console.log('No MIME type, detecting from signature')
                const uint8Array = new Uint8Array(imageBytes)
                
                // Check for PNG signature
                if (uint8Array[0] === 0x89 && uint8Array[1] === 0x50 && uint8Array[2] === 0x4E && uint8Array[3] === 0x47) {
                  console.log('Detected PNG signature')
                  image = await pdfDoc.embedPng(imageBytes)
                }
                // Check for JPEG signature
                else if (uint8Array[0] === 0xFF && uint8Array[1] === 0xD8) {
                  console.log('Detected JPEG signature')
                  image = await pdfDoc.embedJpg(imageBytes)
                }
                // Check for GIF signature
                else if (uint8Array[0] === 0x47 && uint8Array[1] === 0x49 && uint8Array[2] === 0x46) {
                  // Convert GIF to PNG or handle appropriately
                  console.log('Detected GIF signature, converting to PNG')
                  image = await pdfDoc.embedPng(imageBytes)
                }
                // Check for WebP signature
                else if (uint8Array[0] === 0x52 && uint8Array[1] === 0x49 && uint8Array[2] === 0x46 && uint8Array[3] === 0x54) {
                  // Convert WebP to PNG or handle appropriately
                  console.log('Detected WebP signature, converting to PNG')
                  image = await pdfDoc.embedPng(imageBytes)
                }
                else {
                  // Fallback: try PNG first, then JPEG
                  console.log('No signature detected, trying PNG then JPEG')
                  try {
                    image = await pdfDoc.embedPng(imageBytes)
                  } catch (pngError) {
                    try {
                      image = await pdfDoc.embedJpg(imageBytes)
                    } catch (jpgError) {
                      throw new Error('Unsupported image format')
                    }
                  }
                }
              }
              
              console.log('Image embedded successfully, dimensions:', { width: image.width, height: image.height })
            } catch (embedError) {
              console.warn('Failed to embed image:', embedError)
              throw embedError
            }
            
            // Calculate proper dimensions to maintain aspect ratio (like object-contain)
            const imageAspectRatio = image.width / image.height
            const containerAspectRatio = width / height
            
            let drawWidth, drawHeight, drawX, drawY
            
            if (imageAspectRatio > containerAspectRatio) {
              // Image is wider than container, fit to width
              drawWidth = width
              drawHeight = width / imageAspectRatio
              drawX = x
              drawY = pdfPageHeight - y - height + (height - drawHeight) / 2 // Center vertically
            } else {
              // Image is taller than container, fit to height
              drawHeight = height
              drawWidth = height * imageAspectRatio
              drawX = x + (width - drawWidth) / 2 // Center horizontally
              drawY = pdfPageHeight - y - height
            }
            
            console.log('Drawing image:', {
              originalSize: { width: image.width, height: image.height },
              containerSize: { width, height },
              drawSize: { width: drawWidth, height: drawHeight },
              position: { x: drawX, y: drawY },
              rotation: rotation || 0
            })
            
            // Draw the image with proper aspect ratio
            page.drawImage(image, {
              x: drawX,
              y: drawY,
              width: drawWidth,
              height: drawHeight,
              rotate: { angle: rotation || 0, type: 'degrees' }
            })
            
            console.log('Image drawn successfully')
          } catch (imageError) {
            console.warn('Failed to embed image:', imageError)
            // Draw a placeholder rectangle if image fails to load
            page.drawRectangle({
              x: x,
              y: pdfPageHeight - y - height,
              width: width,
              height: height,
              color: rgb(0.9, 0.9, 0.9),
              borderColor: rgb(0.7, 0.7, 0.7),
              borderWidth: 1,
              rotate: { angle: rotation || 0, type: 'degrees' }
            })
          }
        } else if (type === 'drawing' && element.paths && element.paths.length > 0) {
          // Convert drawing color from hex to RGB
          const drawingColor = hexToRgb(element.color || '#000000')
          const strokeThickness = element.thickness || 2
          const color = rgb(drawingColor.r, drawingColor.g, drawingColor.b)
          
          // Draw each path in the drawing element
          for (const path of element.paths) {
            if (path && path.length > 1) {
              // Convert path points to PDF coordinates
              const pdfPoints = path.map(point => ({
                x: point.x,
                y: pdfPageHeight - point.y
              }))
              
              // Draw as a series of connected lines
              for (let i = 0; i < pdfPoints.length - 1; i++) {
                const start = pdfPoints[i]
                const end = pdfPoints[i + 1]
                
                page.drawLine({
                  start: { x: start.x, y: start.y },
                  end: { x: end.x, y: end.y },
                  thickness: strokeThickness,
                  color: color
                })
              }
            }
          }
        } else if (type === 'signature' && element.paths && element.paths.length > 0) {
          // Convert signature color from hex to RGB
          const signatureColor = hexToRgb(element.color || '#000000')
          const strokeThickness = element.thickness || 2
          const color = rgb(signatureColor.r, signatureColor.g, signatureColor.b)
          
          // Draw each path in the signature element
          for (const path of element.paths) {
            if (path && path.length > 1) {
              // Convert path points to PDF coordinates (signature paths are already relative)
              const pdfPoints = path.map(point => ({
                x: x + point.x,
                y: pdfPageHeight - y - point.y
              }))
              
              // Draw as a series of connected lines
              for (let i = 0; i < pdfPoints.length - 1; i++) {
                const start = pdfPoints[i]
                const end = pdfPoints[i + 1]
                
                page.drawLine({
                  start: { x: start.x, y: start.y },
                  end: { x: end.x, y: end.y },
                  thickness: strokeThickness,
                  color: color
                })
              }
            }
          }
        }
      }
    }
    
    // Save the PDF
    const pdfBytes = await pdfDoc.save()
    const blob = new Blob([pdfBytes], { type: 'application/pdf' })
    const url = URL.createObjectURL(blob)
    
    // Download the PDF
    const a = document.createElement('a')
    a.href = url
    a.download = exportMode.value === 'current' ? 'pdf-project-current-page.pdf' : 'pdf-project-all-pages.pdf'
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
    URL.revokeObjectURL(url)
    
  } catch (error) {
    console.error('Error exporting PDF:', error)
    alert('Error exporting PDF. Please try again.')
  }
}

function chaikinSmoothing(points, iterations = 2) {
  for (let i = 0; i < iterations; i++) {
    const newPoints = [points[0]]; // Keep the first point

    for (let j = 0; j < points.length - 1; j++) {
      const p0 = points[j];
      const p1 = points[j + 1];

      const Q = {
        x: 0.75 * p0.x + 0.25 * p1.x,
        y: 0.75 * p0.y + 0.25 * p1.y,
      };
      const R = {
        x: 0.25 * p0.x + 0.75 * p1.x,
        y: 0.25 * p0.y + 0.75 * p1.y,
      };

      newPoints.push(Q, R);
    }

    newPoints.push(points[points.length - 1]); // Keep the last point
    points = newPoints;
  }

  return points;
}

// Fit-curves implementation - converts point data to smooth Bezier curves
function fitCurve(points, error) {
  if (points.length < 2) return [];
  
  // Convert points to the format expected by the algorithm
  const pts = points.map(p => ({ x: p[0], y: p[1] }));
  
  const curves = [];
  const leftTangent = normalize(subtract(pts[1], pts[0]));
  const rightTangent = normalize(subtract(pts[pts.length - 2], pts[pts.length - 1]));
  
  fitCubic(pts, leftTangent, rightTangent, error, curves);
  
  return curves;
}

function fitCubic(points, leftTangent, rightTangent, error, curves) {
  if (points.length === 2) {
    const dist = distance(points[0], points[1]) / 3.0;
    const curve = [
      [points[0].x, points[0].y],
      [points[0].x + leftTangent.x * dist, points[0].y + leftTangent.y * dist],
      [points[1].x + rightTangent.x * dist, points[1].y + rightTangent.y * dist],
      [points[1].x, points[1].y]
    ];
    curves.push(curve);
    return;
  }
  
  const u = chordLengthParameterize(points);
  let maxError = error;
  let splitPoint = Math.floor(points.length / 2);
  
  // Try to fit a curve
  for (let i = 0; i < 4; i++) {
    const curve = generateBezier(points, u, leftTangent, rightTangent);
    const maxErrorResult = computeMaxError(points, curve, u);
    
    if (maxErrorResult.error < error) {
      curves.push(curve);
      return;
    }
    
    if (maxErrorResult.error < maxError) {
      maxError = maxErrorResult.error;
      splitPoint = maxErrorResult.index;
    }
    
    // Reparameterize and try again
    u = reparameterize(points, curve, u);
  }
  
  // If we can't fit a curve, split and try again
  const centerTangent = normalize(subtract(points[splitPoint - 1], points[splitPoint + 1]));
  fitCubic(points.slice(0, splitPoint + 1), leftTangent, centerTangent, error, curves);
  fitCubic(points.slice(splitPoint), negate(centerTangent), rightTangent, error, curves);
}

function chordLengthParameterize(points) {
  const u = [0];
  for (let i = 1; i < points.length; i++) {
    u.push(u[i - 1] + distance(points[i - 1], points[i]));
  }
  for (let i = 1; i < u.length; i++) {
    u[i] /= u[u.length - 1];
  }
  return u;
}

function generateBezier(points, uPrime, leftTangent, rightTangent) {
  const firstPoint = points[0];
  const lastPoint = points[points.length - 1];
  
  // Compute the A's
  const A = [];
  for (let i = 0; i < points.length; i++) {
    const u = uPrime[i];
    const u2 = u * u;
    const u3 = u2 * u;
    const oneMinusU = 1 - u;
    const oneMinusU2 = oneMinusU * oneMinusU;
    const oneMinusU3 = oneMinusU2 * oneMinusU;
    
    A.push([
      oneMinusU3,
      3 * oneMinusU2 * u,
      3 * oneMinusU * u2,
      u3
    ]);
  }
  
  // Compute C and X matrices
  const C = [[0, 0], [0, 0]];
  const X = [0, 0];
  
  for (let i = 0; i < points.length; i++) {
    const a = A[i];
    C[0][0] += a[1] * a[1];
    C[0][1] += a[1] * a[2];
    C[1][0] += a[2] * a[1];
    C[1][1] += a[2] * a[2];
    
    const tmp = subtract(points[i], add(multiply(firstPoint, a[0]), multiply(lastPoint, a[3])));
    X[0] += a[1] * tmp.x;
    X[1] += a[2] * tmp.x;
  }
  
  // Compute the determinants of C and X
  const detC0C1 = C[0][0] * C[1][1] - C[1][0] * C[0][1];
  const detC0X = C[0][0] * X[1] - C[1][0] * X[0];
  const detXC1 = X[0] * C[1][1] - X[1] * C[0][1];
  
  let alphaL, alphaR;
  if (Math.abs(detC0C1) < 1e-10) {
    alphaL = 0;
    alphaR = 0;
  } else {
    alphaL = detXC1 / detC0C1;
    alphaR = detC0X / detC0C1;
  }
  
  // If alpha negative, use the Wu/Barsky heuristic
  if (alphaL < 0 || alphaR < 0) {
    const dist = distance(firstPoint, lastPoint) / 3;
    alphaL = dist;
    alphaR = dist;
  }
  
  const cp1 = add(firstPoint, multiply(leftTangent, alphaL));
  const cp2 = add(lastPoint, multiply(rightTangent, alphaR));
  
  return [
    [firstPoint.x, firstPoint.y],
    [cp1.x, cp1.y],
    [cp2.x, cp2.y],
    [lastPoint.x, lastPoint.y]
  ];
}

function computeMaxError(points, curve, u) {
  let maxError = 0;
  let splitPoint = Math.floor(points.length / 2);
  
  for (let i = 0; i < points.length; i++) {
    const point = points[i];
    const t = u[i];
    const curvePoint = bezierPoint(curve, t);
    const error = distance(point, curvePoint);
    
    if (error > maxError) {
      maxError = error;
      splitPoint = i;
    }
  }
  
  return { error: maxError, index: splitPoint };
}

function bezierPoint(curve, t) {
  const t2 = t * t;
  const t3 = t2 * t;
  const mt = 1 - t;
  const mt2 = mt * mt;
  const mt3 = mt2 * mt;
  
  const x = mt3 * curve[0][0] + 3 * mt2 * t * curve[1][0] + 3 * mt * t2 * curve[2][0] + t3 * curve[3][0];
  const y = mt3 * curve[0][1] + 3 * mt2 * t * curve[1][1] + 3 * mt * t2 * curve[2][1] + t3 * curve[3][1];
  
  return { x, y };
}

function reparameterize(points, curve, u) {
  const newU = [];
  for (let i = 0; i < points.length; i++) {
    newU.push(newtonRaphsonRootFind(curve, points[i], u[i]));
  }
  return newU;
}

function newtonRaphsonRootFind(curve, point, u) {
  const d1 = bezierTangent(curve, u);
  const d2 = bezierTangent(curve, u);
  const p = bezierPoint(curve, u);
  const diff = subtract(point, p);
  
  const numerator = dot(diff, d1);
  const denominator = dot(d1, d1) + dot(diff, d2);
  
  if (Math.abs(denominator) < 1e-10) return u;
  
  return u - numerator / denominator;
}

function bezierTangent(curve, t) {
  const t2 = t * t;
  const mt = 1 - t;
  const mt2 = mt * mt;
  
  const x = -3 * mt2 * curve[0][0] + 3 * mt2 * curve[1][0] - 6 * mt * t * curve[1][0] + 6 * mt * t * curve[2][0] - 3 * t2 * curve[2][0] + 3 * t2 * curve[3][0];
  const y = -3 * mt2 * curve[0][1] + 3 * mt2 * curve[1][1] - 6 * mt * t * curve[1][1] + 6 * mt * t * curve[2][1] - 3 * t2 * curve[2][1] + 3 * t2 * curve[3][1];
  
  return { x, y };
}

// Utility functions
function add(a, b) {
  return { x: a.x + b.x, y: a.y + b.y };
}

function subtract(a, b) {
  return { x: a.x - b.x, y: a.y - b.y };
}

function multiply(v, s) {
  return { x: v.x * s, y: v.y * s };
}

function negate(v) {
  return { x: -v.x, y: -v.y };
}

function normalize(v) {
  const len = Math.sqrt(v.x * v.x + v.y * v.y);
  if (len === 0) return { x: 0, y: 0 };
  return { x: v.x / len, y: v.y / len };
}

function distance(a, b) {
  const dx = a.x - b.x;
  const dy = a.y - b.y;
  return Math.sqrt(dx * dx + dy * dy);
}

function dot(a, b) {
  return a.x * b.x + a.y * b.y;
}

const saveProject = () => {
  const projectData = {
    pages: pages.value,
    mode: mode.value,
    createdAt: new Date().toISOString()
  }
  const blob = new Blob([JSON.stringify(projectData, null, 2)], { type: 'application/json' })
  const url = URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = 'pdf-project.json'
  a.click()
  URL.revokeObjectURL(url)
}

const startTextEditing = (element) => {
  if (element.type === 'text') {
    isEditingText.value = true
    editingTextId.value = element.id
    selectedElement.value = element
  }
}

const stopTextEditing = () => {
  isEditingText.value = false
  editingTextId.value = null
}

const handleTextChange = (event) => {
  if (editingTextId.value) {
    updateElement(editingTextId.value, { content: event.target.value })
  }
}

const handleKeyDown = (event) => {
  if (isEditingText.value) {
    if (event.key === 'Escape') {
      event.preventDefault()
      stopTextEditing()
    } else if (event.key === 'Enter' && event.shiftKey) {
      // Allow multiline with Shift+Enter
      return
    } else if (event.key === 'Enter') {
      event.preventDefault()
      stopTextEditing()
    }
    return
  }
  
  // Copy functionality (Ctrl+C)
  if (event.ctrlKey && event.key === 'c') {
    event.preventDefault()
    copyElement()
  }
  
  // Paste functionality (Ctrl+V)
  if (event.ctrlKey && event.key === 'v') {
    event.preventDefault()
    pasteElement()
  }
  
  if (event.key === 'Delete' || event.key === 'Backspace') {
    event.preventDefault()
    deleteSelectedElement()
  }
}

const handleImageUpload = (event, elementId) => {
  const file = event.target.files[0]
  if (file && file.type.startsWith('image/')) {
    const url = URL.createObjectURL(file)
    updateElement(elementId, { 
      imageUrl: url,
      content: file.name
    })
  }
}

const startDrag = (event, element) => {
  if (isEditingText.value) return
  
  // Auto-deactivate drawing mode when starting to drag other elements
  if (isDrawing.value && element.type !== 'drawing') {
    isDrawing.value = false
    currentDrawingPath.value = []
    drawingElement.value = null
  }
  
  isDragging.value = true
  dragElement.value = element
  const scale = zoom.value / 100
  dragStart.value = {
    x: event.clientX - (element.x * scale),
    y: event.clientY - (element.y * scale)
  }
  
  document.addEventListener('mousemove', handleDrag)
  document.addEventListener('mouseup', stopDrag)
  event.preventDefault()
}

const handleDrag = (event) => {
  if (!isDragging.value || !dragElement.value) return
  
  const scale = zoom.value / 100
  const newX = (event.clientX - dragStart.value.x) / scale
  const newY = (event.clientY - dragStart.value.y) / scale
  
  updateElement(dragElement.value.id, {
    x: Math.max(0, newX),
    y: Math.max(0, newY)
  })
}

const startResize = (event, element, handle) => {
  // Auto-deactivate drawing mode when starting to resize other elements
  if (isDrawing.value && element.type !== 'drawing') {
    isDrawing.value = false
    currentDrawingPath.value = []
    drawingElement.value = null
  }
  
  isResizing.value = true
  dragElement.value = element
  resizeHandle.value = handle
  
  // Store initial values
  dragStart.value = {
    x: event.clientX,
    y: event.clientY,
    width: element.width,
    height: element.height,
    xElem: element.x,
    yElem: element.y
  }
  
  document.addEventListener('mousemove', handleResize)
  document.addEventListener('mouseup', stopResize)
  event.stopPropagation()
  event.preventDefault()
}

const handleResize = (event) => {
  if (!isResizing.value || !dragElement.value) return
  
  const scale = zoom.value / 100
  const deltaX = (event.clientX - dragStart.value.x) / scale
  const deltaY = (event.clientY - dragStart.value.y) / scale
  
  let newWidth = dragStart.value.width
  let newHeight = dragStart.value.height
  let newX = dragStart.value.xElem
  let newY = dragStart.value.yElem
  
  if (dragElement.value.type === 'circle') {
    // For circles, always keep width = height
    let sizeDelta = 0
    switch (resizeHandle.value) {
      case 'nw':
        sizeDelta = Math.max(-deltaX, -deltaY)
        newWidth = newHeight = Math.max(20, dragStart.value.width + sizeDelta)
        newX = dragStart.value.xElem - sizeDelta
        newY = dragStart.value.yElem - sizeDelta
        break
      case 'ne':
        sizeDelta = Math.max(deltaX, -deltaY)
        newWidth = newHeight = Math.max(20, dragStart.value.width + sizeDelta)
        newY = dragStart.value.yElem - sizeDelta
        break
      case 'sw':
        sizeDelta = Math.max(-deltaX, deltaY)
        newWidth = newHeight = Math.max(20, dragStart.value.width + sizeDelta)
        newX = dragStart.value.xElem - sizeDelta
        break
      case 'se':
        sizeDelta = Math.max(deltaX, deltaY)
        newWidth = newHeight = Math.max(20, dragStart.value.width + sizeDelta)
        break
      case 'n':
        sizeDelta = -deltaY
        newWidth = newHeight = Math.max(20, dragStart.value.width + sizeDelta)
        newY = dragStart.value.yElem - sizeDelta
        break
      case 's':
        sizeDelta = deltaY
        newWidth = newHeight = Math.max(20, dragStart.value.width + sizeDelta)
        break
      case 'e':
        sizeDelta = deltaX
        newWidth = newHeight = Math.max(20, dragStart.value.width + sizeDelta)
        break
      case 'w':
        sizeDelta = -deltaX
        newWidth = newHeight = Math.max(20, dragStart.value.width + sizeDelta)
        newX = dragStart.value.xElem - sizeDelta
        break
    }
  } else if (dragElement.value.type === 'drawing' || dragElement.value.type === 'signature') {
    // For drawing and signature elements, scale the paths proportionally
    switch (resizeHandle.value) {
      case 'nw':
        newWidth = Math.max(20, dragStart.value.width - deltaX)
        newHeight = Math.max(20, dragStart.value.height - deltaY)
        newX = dragStart.value.xElem + deltaX
        newY = dragStart.value.yElem + deltaY
        break
      case 'ne':
        newWidth = Math.max(20, dragStart.value.width + deltaX)
        newHeight = Math.max(20, dragStart.value.height - deltaY)
        newY = dragStart.value.yElem + deltaY
        break
      case 'sw':
        newWidth = Math.max(20, dragStart.value.width - deltaX)
        newHeight = Math.max(20, dragStart.value.height + deltaY)
        newX = dragStart.value.xElem + deltaX
        break
      case 'se':
        newWidth = Math.max(20, dragStart.value.width + deltaX)
        newHeight = Math.max(20, dragStart.value.height + deltaY)
        break
      case 'n':
        newHeight = Math.max(20, dragStart.value.height - deltaY)
        newY = dragStart.value.yElem + deltaY
        break
      case 's':
        newHeight = Math.max(20, dragStart.value.height + deltaY)
        break
      case 'e':
        newWidth = Math.max(20, dragStart.value.width + deltaX)
        break
      case 'w':
        newWidth = Math.max(20, dragStart.value.width - deltaX)
        newX = dragStart.value.xElem + deltaX
        break
    }
    
    const scaleX = newWidth / dragStart.value.width
    const scaleY = newHeight / dragStart.value.height
    
    // Scale and reposition all paths in the drawing/signature
    const scaledPaths = dragElement.value.paths.map(path => 
      path.map(point => ({
        x: dragElement.value.type === 'signature' 
          ? point.x * scaleX  // Signature paths are already relative
          : (point.x - dragStart.value.xElem) * scaleX + newX,
        y: dragElement.value.type === 'signature' 
          ? point.y * scaleY  // Signature paths are already relative
          : (point.y - dragStart.value.yElem) * scaleY + newY
      }))
    )
    
    // Update the element with scaled paths
    updateElement(dragElement.value.id, {
      x: newX,
      y: newY,
      width: newWidth,
      height: newHeight,
      paths: scaledPaths
    })
    return // Exit early since we've already updated the element
  } else {
    // For other shapes
    switch (resizeHandle.value) {
      case 'nw':
        newWidth = Math.max(20, dragStart.value.width - deltaX)
        newHeight = Math.max(20, dragStart.value.height - deltaY)
        newX = dragStart.value.xElem + deltaX
        newY = dragStart.value.yElem + deltaY
        break
      case 'ne':
        newWidth = Math.max(20, dragStart.value.width + deltaX)
        newHeight = Math.max(20, dragStart.value.height - deltaY)
        newY = dragStart.value.yElem + deltaY
        break
      case 'sw':
        newWidth = Math.max(20, dragStart.value.width - deltaX)
        newHeight = Math.max(20, dragStart.value.height + deltaY)
        newX = dragStart.value.xElem + deltaX
        break
      case 'se':
        newWidth = Math.max(20, dragStart.value.width + deltaX)
        newHeight = Math.max(20, dragStart.value.height + deltaY)
        break
      case 'n':
        newHeight = Math.max(20, dragStart.value.height - deltaY)
        newY = dragStart.value.yElem + deltaY
        break
      case 's':
        newHeight = Math.max(20, dragStart.value.height + deltaY)
        break
      case 'e':
        newWidth = Math.max(20, dragStart.value.width + deltaX)
        break
      case 'w':
        newWidth = Math.max(20, dragStart.value.width - deltaX)
        newX = dragStart.value.xElem + deltaX
        break
    }
  }
  
  updateElement(dragElement.value.id, {
    width: newWidth,
    height: dragElement.value.type === 'circle' ? newWidth : newHeight,
    x: newX,
    y: newY
  })
}

const stopDrag = () => {
  isDragging.value = false
  dragElement.value = null
  document.removeEventListener('mousemove', handleDrag)
  document.removeEventListener('mouseup', stopDrag)
}

const stopResize = () => {
  isResizing.value = false
  dragElement.value = null
  resizeHandle.value = null
  document.removeEventListener('mousemove', handleResize)
  document.removeEventListener('mouseup', stopResize)
}

const startRotate = (event, element) => {
  // Auto-deactivate drawing mode when starting to rotate other elements
  if (isDrawing.value && element.type !== 'drawing') {
    isDrawing.value = false
    currentDrawingPath.value = []
    drawingElement.value = null
  }
  
  event.stopPropagation()
  isRotating.value = true
  dragElement.value = element
  selectedElement.value = element
  
  const rect = event.currentTarget.getBoundingClientRect()
  const centerX = rect.left + rect.width / 2
  const centerY = rect.top + rect.height / 2
  
  rotateStart.value = {
    x: event.clientX,
    y: event.clientY,
    centerX,
    centerY,
    angle: element.rotation || 0
  }
  
  document.addEventListener('mousemove', handleRotate)
  document.addEventListener('mouseup', stopRotate)
}

const handleRotate = (event) => {
  if (!isRotating.value || !dragElement.value) return
  
  const deltaX = event.clientX - rotateStart.value.centerX
  const deltaY = event.clientY - rotateStart.value.centerY
  
  const angle = Math.atan2(deltaY, deltaX) * (180 / Math.PI)
  const newRotation = (angle + 90) % 360
  
  updateElement(dragElement.value.id, {
    rotation: newRotation
  })
}

const stopRotate = () => {
  isRotating.value = false
  dragElement.value = null
  document.removeEventListener('mousemove', handleRotate)
  document.removeEventListener('mouseup', stopRotate)
}

const handleCanvasClick = (event) => {
  // Deactivate drawing mode when clicking on empty canvas space
  if (isDrawing.value) {
    isDrawing.value = false
    currentDrawingPath.value = []
    drawingElement.value = null
  }
  
  // Deselect any selected element when clicking on empty space
  selectedElement.value = null
}

const startDrawing = () => {
  isDrawing.value = true
  currentDrawingPath.value = []
  drawingElement.value = {
    id: Date.now().toString(),
    type: 'drawing',
    x: 0,
    y: 0,
    width: 0,
    height: 0,
    paths: [],
    color: drawingColor.value,
    thickness: drawingThickness.value,
    rotation: 0,
    zIndex: Math.max(...pages.value[currentPageIndex.value].elements.map(e => e.zIndex), 0) + 1
  }
  
  // Add the drawing element to the page
  const updatedPages = [...pages.value]
  updatedPages[currentPageIndex.value].elements.push(drawingElement.value)
  pages.value = updatedPages
  selectedElement.value = drawingElement.value
}

const handleDrawingStart = (event) => {
  if (!isDrawing.value) return
  
  const rect = event.currentTarget.getBoundingClientRect()
  const scale = zoom.value / 100
  const x = (event.clientX - rect.left) / scale
  const y = (event.clientY - rect.top) / scale
  
  currentDrawingPath.value = [{ x, y }]
  
  // Create new drawing element for this stroke
  drawingElement.value = {
    id: Date.now().toString(),
    type: 'drawing',
    x: x,
    y: y,
    width: 0,
    height: 0,
    paths: [],
    color: drawingColor.value,
    thickness: drawingThickness.value,
    rotation: 0,
    zIndex: Math.max(...pages.value[currentPageIndex.value].elements.map(e => e.zIndex), 0) + 1
  }
  
  // Add the drawing element to the page
  const updatedPages = [...pages.value]
  updatedPages[currentPageIndex.value].elements.push(drawingElement.value)
  pages.value = updatedPages
  
  document.addEventListener('mousemove', handleDrawingMove, { passive: true })
  document.addEventListener('mouseup', handleDrawingEnd)
}

const handleDrawingMove = (event) => {
  if (!isDrawing.value || !drawingElement.value) return
  
  const rect = document.querySelector('.canvas-container')?.getBoundingClientRect()
  if (!rect) return
  
  const scale = zoom.value / 100
  const x = (event.clientX - rect.left) / scale
  const y = (event.clientY - rect.top) / scale
  
  // Add point to current path
  currentDrawingPath.value.push({ x, y })
  
  // Update the drawing element with the current path for real-time display
  const updatedPages = [...pages.value]
  const pageIndex = currentPageIndex.value
  const elementIndex = updatedPages[pageIndex].elements.findIndex(el => el.id === drawingElement.value.id)
  
  if (elementIndex !== -1) {
    updatedPages[pageIndex].elements[elementIndex].paths = [currentDrawingPath.value]
    
    // Calculate current bounding box for real-time sizing
    if (currentDrawingPath.value.length > 0) {
      const minX = Math.min(...currentDrawingPath.value.map(p => p.x))
      const maxX = Math.max(...currentDrawingPath.value.map(p => p.x))
      const minY = Math.min(...currentDrawingPath.value.map(p => p.y))
      const maxY = Math.max(...currentDrawingPath.value.map(p => p.y))
      
      updatedPages[pageIndex].elements[elementIndex].x = minX
      updatedPages[pageIndex].elements[elementIndex].y = minY
      updatedPages[pageIndex].elements[elementIndex].width = maxX - minX
      updatedPages[pageIndex].elements[elementIndex].height = maxY - minY
    }
    
    pages.value = updatedPages
  }
}

const handleDrawingEnd = () => {
  if (!isDrawing.value || !drawingElement.value) return
  
  // Final calculation of bounding box for the drawing
  const allPoints = drawingElement.value.paths.flat()
  if (allPoints.length > 0) {
    const minX = Math.min(...allPoints.map(p => p.x))
    const maxX = Math.max(...allPoints.map(p => p.x))
    const minY = Math.min(...allPoints.map(p => p.y))
    const maxY = Math.max(...allPoints.map(p => p.y))
    
    // Ensure minimum dimensions for proper selection and dragging
    const width = Math.max(20, maxX - minX)
    const height = Math.max(20, maxY - minY)
    
    drawingElement.value.x = minX
    drawingElement.value.y = minY
    drawingElement.value.width = width
    drawingElement.value.height = height
  } else {
    // If no points, set minimum dimensions
    drawingElement.value.width = Math.max(20, drawingElement.value.width)
    drawingElement.value.height = Math.max(20, drawingElement.value.height)
  }
  
  // Update the drawing element in the page with final dimensions
  const updatedPages = [...pages.value]
  const pageIndex = currentPageIndex.value
  const elementIndex = updatedPages[pageIndex].elements.findIndex(el => el.id === drawingElement.value.id)
  
  if (elementIndex !== -1) {
    updatedPages[pageIndex].elements[elementIndex] = { ...drawingElement.value }
    pages.value = updatedPages
  }
  
  currentDrawingPath.value = []
  drawingElement.value = null
  
  document.removeEventListener('mousemove', handleDrawingMove)
  document.removeEventListener('mouseup', handleDrawingEnd)
  
  addToHistory(pages.value)
}

// Page size and orientation methods
const updatePageSize = () => {
  if (selectedPageSize.value === 'custom') {
    // Keep current dimensions for custom
    return
  }
  
  const size = pageSizes[selectedPageSize.value]
  if (size) {
    if (pageOrientation.value === 'portrait') {
      pageWidth.value = size.width
      pageHeight.value = size.height
    } else {
      pageWidth.value = size.height
      pageHeight.value = size.width
    }
    
    // Update all pages with new dimensions
    pages.value = pages.value.map(page => ({
      ...page,
      width: pageWidth.value,
      height: pageHeight.value
    }))
  }
}

const setOrientation = (orientation) => {
  pageOrientation.value = orientation
  
  // Swap width and height for all pages
  pages.value = pages.value.map(page => ({
    ...page,
    width: pageHeight.value,
    height: pageWidth.value
  }))
  
  // Update current dimensions
  const temp = pageWidth.value
  pageWidth.value = pageHeight.value
  pageHeight.value = temp
}

// Initialize pages with current dimensions
const pages = ref([
  {
    id: 1,
    width: pageWidth.value,
    height: pageHeight.value,
    elements: []
  }
])

// Initialize pages
onMounted(() => {
  console.log('Component mounted, mode:', mode.value, 'uploadedFile:', uploadedFile.value)
  console.log('Current pages:', pages.value)
  console.log('Page width/height:', pageWidth.value, pageHeight.value)
  
  if (mode.value === 'create') {
    console.log('Creating new page for create mode')
    const newPage = {
      id: '1',
      elements: [],
      width: pageWidth.value,
      height: pageHeight.value
    }
    pages.value = [newPage]
    addToHistory([newPage])
    console.log('Created new page:', newPage)
  } else if (mode.value === 'edit' && uploadedFile.value) {
    // Load the uploaded PDF file
    console.log('Loading PDF on mount:', uploadedFile.value.name)
    loadPdfFile(uploadedFile.value)
  } else if (mode.value === 'edit' && !uploadedFile.value) {
    console.log('Edit mode but no uploaded file found')
    // Create a default page for edit mode if no file
    const newPage = {
      id: '1',
      elements: [],
      width: pageWidth.value,
      height: pageHeight.value
    }
    pages.value = [newPage]
    addToHistory([newPage])
    console.log('Created default page for edit mode:', newPage)
  }
  document.addEventListener('keydown', handleKeyDown)
})

// Clean up event listener
onUnmounted(() => {
  document.removeEventListener('keydown', handleKeyDown)
})

const toggleDrawingMode = () => {
  isDrawing.value = !isDrawing.value
  if (isDrawing.value) {
    // Optionally, visually indicate drawing mode or reset state
    currentDrawingPath.value = []
    drawingElement.value = null
  }
}

// Signature functions
const openSignatureModal = () => {
  showSignatureModal.value = true
  currentSignaturePath.value = []
  allSignatureStrokes.value = []
  isDrawingSignature.value = false
  // Initialize canvas when modal opens
  nextTick(() => {
    if (signatureCanvas.value) {
      const ctx = signatureCanvas.value.getContext('2d')
      ctx.clearRect(0, 0, signatureCanvas.value.width, signatureCanvas.value.height)
    }
  })
}

const closeSignatureModal = () => {
  showSignatureModal.value = false
  currentSignaturePath.value = []
  allSignatureStrokes.value = []
  isDrawingSignature.value = false
}

const clearSignature = () => {
  if (signatureCanvas.value) {
    const ctx = signatureCanvas.value.getContext('2d')
    ctx.clearRect(0, 0, signatureCanvas.value.width, signatureCanvas.value.height)
  }
  currentSignaturePath.value = []
  allSignatureStrokes.value = []
}

const startSignatureDrawing = (event) => {
  isDrawingSignature.value = true
  const rect = signatureCanvas.value.getBoundingClientRect()
  const x = event.clientX - rect.left
  const y = event.clientY - rect.top
  
  // Start a new path for this stroke
  currentSignaturePath.value = [{ x, y }]
  
  const ctx = signatureCanvas.value.getContext('2d')
  ctx.beginPath()
  ctx.moveTo(x, y)
  ctx.strokeStyle = signatureColor.value
  ctx.lineWidth = signatureThickness.value
  ctx.lineCap = 'round'
  ctx.lineJoin = 'round'
}

const handleSignatureDrawing = (event) => {
  if (!isDrawingSignature.value) return
  
  const rect = signatureCanvas.value.getBoundingClientRect()
  const x = event.clientX - rect.left
  const y = event.clientY - rect.top
  
  currentSignaturePath.value.push({ x, y })
  
  const ctx = signatureCanvas.value.getContext('2d')
  ctx.lineTo(x, y)
  ctx.stroke()
}

const stopSignatureDrawing = () => {
  isDrawingSignature.value = false
  // Save the current stroke to all strokes
  if (currentSignaturePath.value.length > 0) {
    allSignatureStrokes.value.push([...currentSignaturePath.value])
  }
}

const addSignatureToPage = () => {
  // Combine all strokes including the current one
  const allStrokes = [...allSignatureStrokes.value]
  if (currentSignaturePath.value.length > 0) {
    allStrokes.push([...currentSignaturePath.value])
  }
  
  if (allStrokes.length === 0) return
  
  // Flatten all points to calculate bounding box
  const allPoints = allStrokes.flat()
  if (allPoints.length === 0) return
  
  // Calculate bounding box of all signature points
  const minX = Math.min(...allPoints.map(p => p.x))
  const maxX = Math.max(...allPoints.map(p => p.x))
  const minY = Math.min(...allPoints.map(p => p.y))
  const maxY = Math.max(...allPoints.map(p => p.y))
  
  const width = Math.max(20, maxX - minX)
  const height = Math.max(20, maxY - minY)
  
  // Normalize all strokes relative to the bounding box
  const normalizedPaths = allStrokes.map(stroke => 
    stroke.map(point => ({
      x: point.x - minX,
      y: point.y - minY
    }))
  )
  
  // Create signature element
  const signatureElement = {
    id: Date.now().toString(),
    type: 'signature',
    x: 100,
    y: 100,
    width: width,
    height: height,
    paths: normalizedPaths,
    color: signatureColor.value,
    thickness: signatureThickness.value,
    rotation: 0,
    zIndex: Math.max(...pages.value[currentPageIndex.value].elements.map(e => e.zIndex), 0) + 1
  }
  
  // Add to page
  const updatedPages = [...pages.value]
  updatedPages[currentPageIndex.value].elements.push(signatureElement)
  pages.value = updatedPages
  addToHistory(updatedPages)
  selectedElement.value = signatureElement
  
  // Close modal
  closeSignatureModal()
}
</script> 