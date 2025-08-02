<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# 📝 PDF Customization Tool

A flexible Vue.js tool that allows users to either **import and edit existing PDFs** or **create new PDFs from scratch** with intuitive drag-and-drop functionality, element editing, and export options.

## 🚀 Features

### 🔄 Mode Selection
Users can choose to:
- ✅ **Edit Existing PDF** - Upload and modify existing PDF documents
- 🆕 **Create New PDF** - Start from scratch and build custom PDF documents

### 📥 Edit Existing PDF
- Upload PDF and preview all pages
- Select and edit individual pages
- Overlay elements on top of PDF:
  - ✏️ Text with custom styling
  - 🖼️ Images
  - 🔲 Shapes (rectangles, circles)
- Modify element properties:
  - Position, Size, Color, Font, Rotation
- Rearrange layers (Bring to front, Send to back)
- Undo/Redo support
- Preserve original layout

### 🧾 Create New PDF
- Choose page size (A4, Letter, Custom)
- Add elements:
  - Text blocks with custom styling
  - Upload and position images
  - Draw shapes with color and stroke controls
- Multi-page support
- Page navigation and duplication
- Snap-to-grid and alignment guides

### 🔁 Common Features
- Zoom and pan functionality
- Grid and ruler options
- Page management (add, delete, duplicate)
- Export to:
  - ✅ Downloadable PDF
  - 💾 Editable project file (JSON)
- Save & Load project state
- Keyboard shortcuts for power users

### 💡 Advanced Features
- 📎 Add hyperlinks and clickable areas
- 📝 Embed form fields (text input, checkbox, radio button)
- 🔐 Password protect exported PDFs
- 🕓 View version history of edits

## 🧰 Tech Stack

### Frontend
- **Vue 3** (Composition API)
- **Vue Router** for navigation
- **Tailwind CSS** for styling
- **Heroicons** for icons
- **pdf-lib** for PDF manipulation
- **Fabric.js** for canvas rendering (planned)

### Backend
- **Laravel** (PHP framework)
- File uploads and storage
- Storing saved projects
- Exporting from JSON to PDF server-side

## 🚀 Getting Started

### Prerequisites
- Node.js (v16 or higher)
- PHP 8.1+
- Composer
- Laravel

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd pdf-editing-tool
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Set up environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Run database migrations**
   ```bash
   php artisan migrate
   ```

6. **Start the development server**
   ```bash
   # Terminal 1: Start Laravel server
   php artisan serve
   
   # Terminal 2: Start Vite dev server
   npm run dev
   ```

7. **Open your browser**
   Navigate to `http://localhost:8000`

## 📁 Project Structure

```
pdf-editing-tool/
├── resources/
│   ├── js/
│   │   ├── components/
│   │   │   ├── LandingPage.vue      # Landing page with mode selection
│   │   │   └── PDFEditor.vue        # Main PDF editor component
│   │   ├── App.vue                  # Root Vue component
│   │   └── app.js                   # Vue app entry point
│   ├── views/
│   │   └── welcome.blade.php        # Main Laravel view
│   └── css/
│       └── app.css                  # Tailwind CSS
├── public/                          # Public assets
├── routes/
│   └── web.php                      # Laravel routes
└── package.json                     # Node.js dependencies
```

## 🎯 Usage

### Landing Page
1. Choose between "Edit Existing PDF" or "Create New PDF"
2. For editing: Upload a PDF file
3. For creating: Start with a blank canvas

### PDF Editor Interface
- **Left Sidebar**: Tools and properties
  - Elements tab: Add text, images, shapes
  - Pages tab: Manage multiple pages
  - Properties tab: Edit selected element properties
- **Main Canvas**: Visual editing area
- **Top Toolbar**: Undo/Redo, Grid/Ruler, Save/Export

### Adding Elements
1. Select element type from the Elements tab
2. Click on canvas to place element
3. Use Properties tab to customize appearance
4. Drag to reposition, resize handles to adjust size

### Export Options
- **Save Project**: Download as JSON file for later editing
- **Export PDF**: Generate final PDF document

## 🔧 Development

### Vue.js Components
The application uses Vue 3 with Composition API:

- **LandingPage.vue**: Mode selection interface
- **PDFEditor.vue**: Main editor with canvas and tools

### State Management
- Uses Vue's reactive system for state management
- History tracking for undo/redo functionality
- Element selection and property editing

### Styling
- Tailwind CSS for utility-first styling
- Responsive design for different screen sizes
- Dark mode support (planned)

## 🚧 Roadmap

### Phase 1 (Current)
- ✅ Basic Vue.js conversion
- ✅ Landing page with mode selection
- ✅ PDF editor interface
- ✅ Element addition and editing
- ✅ Basic canvas rendering

### Phase 2 (Next)
- 🔄 PDF file upload and parsing
- 🔄 Real PDF rendering on canvas
- 🔄 Drag-and-drop functionality
- 🔄 Advanced element properties

### Phase 3 (Future)
- 📋 Multi-page support
- 📋 Export to PDF functionality
- 📋 Save/load project files
- 📋 Advanced features (hyperlinks, forms)

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Original React implementation for inspiration
- Vue.js community for excellent documentation
- Laravel team for the robust PHP framework
- Tailwind CSS for the utility-first CSS framework
