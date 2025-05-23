# Patitas.sytes.net

**Tienda online de productos para mascotas**  
Desarrollada e implementada en **XAMPP** con PHP, MySQL, HTML y CSS.

---

## 📋 Descripción

Patitas.sytes.net es una plataforma de comercio electrónico para la venta de alimentos, juguetes, accesorios e higiene para mascotas (perros, gatos y otras especies). Ofrece a los usuarios registro, gestión de perfil, carrito de compra, pasarela de pago, historial de pedidos y exportación del catálogo en XML. Diseñada para ser accesible, intuitiva y escalable, facilita la digitalización de pequeños negocios.

---

## ⚙️ Prerrequisitos

- **XAMPP** (Apache + MySQL + PHP) instalado en tu sistema  
- Navegador web moderno (Chrome, Firefox, Edge, etc.)  
- Editor de código (VS Code, Sublime Text, PHPStorm…)

---

## 🛠 Instalación

1. **Descarga el proyecto**  
   Clona o copia la carpeta `patitas.sytes.net` dentro de la carpeta `htdocs` de tu instalación XAMPP (por ejemplo, `C:\xampp\htdocs\patitas.sytes.net`).

2. **Arranca servicios**  
   - Abre el panel de control de XAMPP.  
   - Inicia **Apache** y **MySQL**.

3. **Configura la base de datos**  
   - Abre **phpMyAdmin** (`http://localhost/phpmyadmin`).  
   - Crea una base de datos llamada `patitas_db` (o el nombre que prefieras).  
   - Importa el archivo SQL (por ejemplo, `patitas_db.sql`) con la estructura y datos iniciales.

4. **Ajusta la conexión**  
   - Edita `db_config.php` dentro del proyecto y actualiza los parámetros:
     ```php
     <?php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');         // tu contraseña de MySQL (por defecto vacía)
     define('DB_NAME', 'patitas_db');
     ?>
     ```

5. **Accede a la app**  
   - Ve a `http://patitas.sytes.net` en tu navegador.  
   - Regístrate como nuevo usuario o inicia sesión con credenciales existentes.

---

---

## 🗄️ Base de datos

- **Usuarios**: datos y credenciales de usuarios.  
- **Categorías**: (Perros, Gatos, Otras, Higiene).  
- **Productos**: catálogo con descripción, precio, stock, imagen y categoría.  
- **Carrito** & **carrito_detalle**: gestión de compras pendientes.  
- **Pedidos** & **pedido_detalle**: registro de compras finalizadas.  
- **Direcciones**: direcciones de envío asociadas a cada usuario.  
- **Métodos_pago**: tarjetas, PayPal, transferencias guardadas.

---

## 🚀 Funcionalidades

- **Registro / Login**  
- **Gestión de perfil** (datos, cambio de contraseña)  
- **CRUD de direcciones y métodos de pago**  
- **Carrito de compra** (añadir, actualizar, eliminar)  
- **Checkout**: selección de dirección, método de pago, confirmación de pedido  
- **Historial de pedidos** y detalle de cada uno  
- **Exportación del catálogo** en formato XML  
- **Mensajes de éxito/error** en todas las operaciones

---

## 💻 Tecnologías

- **PHP 7+**  
- **MySQL**  
- **HTML5 & CSS3** (Responsive design)  
- **XAMPP** (Server local Apache/MySQL)  

---

## ⚡ Mejoras futuras

- Integración SEO  
- Marketing por correo electrónico  
- App móvil / PWA  
- Recomendaciones con IA  
- Sistema de notificaciones

---

## 📝 Licencia

Proyecto académico desarrollado como entrega de la asignatura “Lenguajes de marcas y sistemas de gestión de la información”.  
Uso libre para fines educativos.
