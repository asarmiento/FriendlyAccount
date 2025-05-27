# 🎨 Friendly Modules - Corporate Design System

## Estructura del Sistema de Diseño

Este sistema de diseño está organizado usando la metodología **ITCSS (Inverted Triangle CSS)** para mantener un código escalable y mantenible.

### 📁 Estructura de Carpetas

```
resources/sass/
├── settings/          # Variables globales y configuraciones
│   ├── _variables.scss    # Variables del sistema
│   ├── _colors.scss       # Paleta de colores corporativa
│   ├── _typography.scss   # Sistema tipográfico
│   ├── _spacing.scss      # Sistema de espaciado
│   └── _breakpoints.scss  # Breakpoints responsive
├── tools/             # Mixins y funciones
│   ├── _mixins.scss       # Mixins reutilizables
│   └── _functions.scss    # Funciones Sass
├── generic/           # Resets y normalizaciones
│   ├── _normalize.scss    # Normalize CSS
│   ├── _reset.scss        # Reset moderno
│   └── _box-sizing.scss   # Box model
├── elements/          # Elementos HTML base
│   ├── _page.scss         # Elementos de página
│   ├── _headings.scss     # Títulos
│   ├── _links.scss        # Enlaces
│   ├── _forms.scss        # Formularios
│   └── _tables.scss       # Tablas
├── objects/           # Patrones de diseño
│   ├── _wrapper.scss      # Contenedores
│   ├── _layout.scss       # Layouts
│   ├── _grid.scss         # Sistema de grid
│   └── _media.scss        # Objeto media
├── components/        # Componentes UI
│   ├── _buttons.scss      # Sistema de botones
│   ├── _forms.scss        # Componentes de formularios
│   ├── _navigation.scss   # Navegación
│   ├── _sidebar.scss      # Sidebar corporativo
│   ├── _header.scss       # Header de página
│   ├── _cards.scss        # Sistema de cards
│   ├── _tables.scss       # Componentes de tablas
│   ├── _modals.scss       # Modales
│   └── _alerts.scss       # Alertas
└── utilities/         # Utilidades
    ├── _widths.scss       # Anchos
    ├── _headings.scss     # Utilidades de títulos
    ├── _spacing.scss      # Espaciado
    ├── _colors.scss       # Colores
    ├── _typography.scss   # Tipografía
    └── _visibility.scss   # Visibilidad
```

## 🎨 Paleta de Colores

### Colores Primarios (Marca)
- **Primary 700**: `#003e59` (Color principal de marca)
- **Primary 600**: `#004f73`
- **Primary 500**: `#00608d`
- **Primary 400**: `#2a7ba7`

### Colores Secundarios
- **Blue**: `#4a90e2` (Azul profesional)
- **Green**: `#27ae60` (Verde de éxito)
- **Orange**: `#f39c12` (Naranja de advertencia)
- **Red**: `#e74c3c` (Rojo de error)
- **Purple**: `#8e44ad` (Púrpura de acento)

### Colores Neutros
- **Neutral 900**: `#1a1a1a` (Texto principal)
- **Neutral 600**: `#6b7280` (Texto secundario)
- **Neutral 500**: `#9ca3af` (Texto terciario)
- **Neutral 300**: `#e5e7eb` (Bordes)
- **Neutral 100**: `#f9fafb` (Fondos)

## 📏 Sistema de Espaciado

Basado en una escala de 8px para consistencia:

- `$spacing-1`: 4px
- `$spacing-2`: 8px
- `$spacing-3`: 12px
- `$spacing-4`: 16px
- `$spacing-6`: 24px
- `$spacing-8`: 32px

## 🔤 Sistema Tipográfico

### Familias de Fuentes
- **Primaria**: Inter, Segoe UI, Roboto, sans-serif
- **Secundaria**: SF Pro Display, sans-serif
- **Monospace**: Fira Code, Consolas, Monaco

### Escala Tipográfica
- `$font-size-xs`: 12px
- `$font-size-sm`: 14px
- `$font-size-base`: 16px
- `$font-size-lg`: 18px
- `$font-size-xl`: 20px
- `$font-size-2xl`: 24px

## 📱 Breakpoints Responsive

- **xs**: 0px
- **sm**: 576px
- **md**: 768px
- **lg**: 992px
- **xl**: 1200px
- **2xl**: 1400px

## 🧩 Componentes Principales

### Sidebar Corporativo
```scss
.sidebar {
  // Sidebar moderno con gradiente corporativo
  // Responsive y colapsable
}
```

### Sistema de Cards
```scss
.card {
  // Cards con variantes semánticas
  // --primary, --success, --warning, --danger, --info
}
```

### Sistema de Botones
```scss
.btn {
  // Botones con múltiples variantes y tamaños
  // --primary, --secondary, --outline, --ghost
}
```

## 🔧 Uso y Compilación

### Desarrollo
```bash
npm run dev
```

### Producción
```bash
npm run build
```

### Watching
```bash
npm run watch
```

## 🔄 Compatibilidad Legacy

El sistema mantiene compatibilidad con clases existentes:

- `.azul`, `.rojo`, `.morado`, `.amarillo` (colores legacy)
- `.content-box-*` (cards legacy)
- `.Menu` (sidebar legacy)
- `.ocultar`, `.mostrar` (visibilidad legacy)

## 📋 Metodología BEM

Los componentes nuevos siguen la metodología BEM:

```scss
.component {}           // Bloque
.component__element {}  // Elemento
.component--modifier {} // Modificador
```

## 🚀 Características

- ✅ **Diseño Corporativo**: Colores y tipografía profesional
- ✅ **Responsive**: Adaptable a todos los dispositivos
- ✅ **Modular**: Arquitectura ITCSS escalable
- ✅ **Accesible**: Cumple estándares de accesibilidad
- ✅ **Performante**: CSS optimizado y minificado
- ✅ **Compatible**: Mantiene estilos legacy
- ✅ **Documentado**: Código bien documentado 