# Requerimientos del Sistema VIVA — v2
> Última actualización: 2026-02-21 | Estado: En construcción (por módulos)

---

## Índice de Módulos

| # | Módulo | RF | RNF | Estado |
|---|--------|----|-----|--------|
| 1 | [Autenticación y Registro](#1-autenticación-y-registro) | RF-01 al RF-07 | RNF-01 al RNF-03 | ✅ Implementado |
| 2 | [Home / Landing Page](#2-home--landing-page) | RF-08 al RF-12 | RNF-04 | ✅ Implementado |
| 3 | [Catálogo de Productos](#3-catálogo-de-productos) | RF-13 al RF-18 | RNF-05 al RNF-06 | ✅ Implementado |
| 4 | [Detalle de Producto](#4-detalle-de-producto) | RF-19 al RF-23 | RNF-07 | ✅ Implementado |
| 5 | [Stands / Productores](#5-stands--productores) | RF-24 al RF-27 | RNF-08 | ✅ Implementado |
| 6 | [Carrito de Compras](#6-carrito-de-compras) | RF-28 al RF-33 | RNF-09 | ✅ Implementado |
| 7 | [Checkout y Pago (ePayco)](#7-checkout-y-pago-epayco) | RF-34 al RF-44 | RNF-10 al RNF-12 | ✅ Implementado |
| 8 | [Facturación](#8-facturación) | RF-45 al RF-51 | RNF-13 al RNF-14 | ✅ Implementado |
| 9 | [Perfil de Usuario](#9-perfil-de-usuario) | RF-52 al RF-57 | RNF-15 | 🔄 Parcial |
| 10 | [Mis Productos (Vendedor)](#10-mis-productos-vendedor) | RF-58 al RF-65 | RNF-16 | 🔄 Parcial |
| 11 | [Registro de Vendedor](#11-registro-de-vendedor) | RF-66 al RF-70 | RNF-17 | ✅ Implementado |

---

## Requerimientos No Funcionales Globales

| ID | Requerimiento |
|----|--------------|
| RNF-G1 | El sistema debe usar PostgreSQL como motor de base de datos |
| RNF-G2 | Todas las operaciones de INSERT/UPDATE deben ejecutarse mediante funciones SQL (`fun_c_*`, `fun_u_*`) |
| RNF-G3 | Los prepared statements deben estar centralizados en `Database.php` (patrón Singleton) |
| RNF-G4 | El sistema debe prevenir inyección SQL usando PDO con parámetros binding |
| RNF-G5 | El sistema debe correr sobre Apache + PHP en Windows (desarrollo) |
| RNF-G6 | La URL base (`BASE_URL`) debe ser configurable por entorno sin modificar código |
| RNF-G7 | Los estilos deben usar Vanilla CSS con sistema de variables (`variables.css`) + Tailwind para componentes específicos |
| RNF-G8 | Las páginas deben ser responsivas (mobile-first) |

---

## 1. Autenticación y Registro

### Requerimientos Funcionales

| ID | Descripción | Implementado en |
|----|-------------|-----------------|
| RF-01 | El sistema debe permitir al usuario registrarse con nombre, email y contraseña | `registro.php` |
| RF-02 | El sistema debe autenticar usuarios con email y contraseña | `login.php` |
| RF-03 | Las contraseñas deben almacenarse hasheadas (bcrypt) | `Database.php` |
| RF-04 | El sistema debe mantener la sesión del usuario autenticado via `$_SESSION` | `index.php` |
| RF-05 | El sistema debe permitir cerrar sesión destruyendo la sesión activa | `logout.php` |
| RF-06 | Las rutas protegidas deben redirigir al login si no hay sesión activa | `index.php` (router) |
| RF-07 | El sistema debe recuperar la sesión del usuario desde el `x_id_invoice` de ePayco al regresar del pago | `checkout_response.php` |

### Requerimientos No Funcionales

| ID | Descripción |
|----|-------------|
| RNF-01 | La sesión debe persistir entre cambios de host (`localhost` ↔ `127.0.0.1`) usando `session_name` consistente |
| RNF-02 | El login debe responder en menos de 500ms |
| RNF-03 | Los errores de autenticación no deben revelar si el email existe o no |

---

## 2. Home / Landing Page

### Requerimientos Funcionales

| ID | Descripción | Implementado en |
|----|-------------|-----------------|
| RF-08 | La página de inicio debe mostrar productos destacados dinámicos | `index.php` → `fun_obtener_productos_destacados` |
| RF-09 | La página de inicio debe mostrar categorías disponibles con imágenes | `index.view.php` |
| RF-10 | La página de inicio debe mostrar la sección "Sobre nosotros / Historia" | `index.view.php` |
| RF-11 | La página de inicio debe mostrar afiliados/marcas aliadas | `index.view.php` |
| RF-12 | La página de inicio debe tener sección de newsletter (formulario de suscripción) | `index.view.php` |

### Requerimientos No Funcionales

| ID | Descripción |
|----|-------------|
| RNF-04 | El Home debe cargar en menos de 2 segundos en conexión estándar |

---

## 3. Catálogo de Productos

### Requerimientos Funcionales

| ID | Descripción | Implementado en |
|----|-------------|-----------------|
| RF-13 | El catálogo debe listar todos los productos activos con imagen, nombre y precio | `catalogo.php` |
| RF-14 | El catálogo debe permitir filtrar por categoría | `api/get_catalogo.php` |
| RF-15 | El catálogo debe permitir filtrar por oficio artesanal | `api/get_catalogo.php` |
| RF-16 | El catálogo debe permitir filtrar por materia prima | `api/get_catalogo.php` |
| RF-17 | El catálogo debe permitir filtrar por color | `api/get_catalogo.php` |
| RF-18 | Cada tarjeta de producto debe enlazar al detalle del producto | `catalogo.view.php` |

### Requerimientos No Funcionales

| ID | Descripción |
|----|-------------|
| RNF-05 | Los filtros deben aplicarse sin recargar la página (AJAX) |
| RNF-06 | El catálogo debe soportar paginación o carga progresiva para grandes volúmenes |

---

## 4. Detalle de Producto

### Requerimientos Funcionales

| ID | Descripción | Implementado en |
|----|-------------|-----------------|
| RF-19 | La página de detalle debe mostrar imágenes del producto con zoom | `producto.view.php` |
| RF-20 | La página de detalle debe mostrar nombre, descripción, precio y stock disponible | `producto.php` → `fun_obtener_detalle_producto` |
| RF-21 | La página de detalle debe mostrar la "Stand Card" del productor (logo, nombre, slogan) | `producto.view.php` |
| RF-22 | El usuario debe poder seleccionar cantidad y agregar al carrito desde el detalle | `producto_detalle.js` |
| RF-23 | La página de detalle debe mostrar calificación del producto con estrellas | `producto.view.php` |

### Requerimientos No Funcionales

| ID | Descripción |
|----|-------------|
| RNF-07 | El zoom de imagen debe funcionar sin librerías externas (JavaScript nativo) |

---

## 5. Stands / Productores

### Requerimientos Funcionales

| ID | Descripción | Implementado en |
|----|-------------|-----------------|
| RF-24 | El directorio de stands debe listar todos los productores activos | `stands.php` |
| RF-25 | Cada stand debe tener página de detalle con información del productor | `stand_detail.php` |
| RF-26 | La página de stand debe mostrar los productos del productor | `stand_detail.view.php` |
| RF-27 | La página de stand debe mostrar descripción, logo y datos de contacto del productor | `stand_detail.view.php` |

### Requerimientos No Funcionales

| ID | Descripción |
|----|-------------|
| RNF-08 | El directorio de stands debe ser accesible sin autenticación |

---

## 6. Carrito de Compras

### Requerimientos Funcionales

| ID | Descripción | Implementado en |
|----|-------------|-----------------|
| RF-28 | El sistema debe permitir agregar productos al carrito | `api/cart.php` → `fun_carrito('agregar')` |
| RF-29 | El sistema debe permitir cambiar la cantidad de un producto en el carrito | `api/cart.php` → `fun_carrito('actualizar')` |
| RF-30 | El sistema debe permitir eliminar productos del carrito | `api/cart.php` → `fun_carrito('eliminar')` |
| RF-31 | El sistema debe mostrar el resumen del carrito con subtotal, items y total | `checkout.view.php` |
| RF-32 | El carrito debe vaciarse automáticamente tras una facturación exitosa | `fun_facturar` → `fun_carrito('limpiar')` |
| RF-33 | El carrito debe persistir en base de datos (no en sesión) para sobrevivir cierres de sesión | `tab_carrito` |

### Requerimientos No Funcionales

| ID | Descripción |
|----|-------------|
| RNF-09 | Las operaciones del carrito deben responder vía AJAX sin recargar la página |

---

## 7. Checkout y Pago (ePayco)

### Requerimientos Funcionales

| ID | Descripción | Implementado en |
|----|-------------|-----------------|
| RF-34 | El checkout debe requerir autenticación previa | `checkout.php` |
| RF-35 | El checkout debe mostrar los productos del carrito con imagen, nombre, cantidad y precio | `checkout.view.php` |
| RF-36 | El checkout debe permitir al usuario ingresar/editar su dirección de envío | `checkout.view.php` → `api/guardar_cliente.php` |
| RF-37 | El formulario de dirección debe cargar ciudades dinámicamente según el departamento seleccionado | `api/get_ciudades.php` (AJAX) |
| RF-38 | La dirección de envío debe guardarse en `tab_clientes` mediante `fun_c_cliente` | `api/guardar_cliente.php` |
| RF-39 | Si el usuario ya tiene dirección guardada, debe pre-llenarse el formulario | `checkout.php` → `obtenerDireccionCliente` |
| RF-40 | El botón "Pagar Seguro" debe habilitarse solo después de guardar la dirección | `checkout.view.php` (JS) |
| RF-41 | El pago debe procesarse mediante el widget de ePayco | `checkout.view.php` → SDK ePayco |
| RF-42 | La referencia de pago debe incluir el `id_user` para recuperar la sesión en la respuesta | `checkout.php` → `'VIVA-' . time() . '-' . $id_user` |
| RF-43 | Al recibir la respuesta de ePayco, el sistema debe validar la transacción contra la API de ePayco | `checkout_response.php` |
| RF-44 | Solo las transacciones con `x_cod_response = 1` (Aceptada) deben disparar la facturación | `checkout_response.php` |

### Requerimientos No Funcionales

| ID | Descripción |
|----|-------------|
| RNF-10 | La comunicación con ePayco debe usar HTTPS |
| RNF-11 | La sesión del usuario debe recuperarse desde `x_id_invoice` cuando ePayco elimina la cookie (cross-site) |
| RNF-12 | La llave pública de ePayco debe estar en `.env`, nunca hardcodeada |

---

## 8. Facturación

### Requerimientos Funcionales

| ID | Descripción | Implementado en |
|----|-------------|-----------------|
| RF-45 | El sistema debe crear un encabezado de factura con número consecutivo, fecha, cliente, dirección y datos ePayco | `fun_c_enc_fact` |
| RF-46 | El número de factura debe generarse a partir del consecutivo en `tab_pmtros` | `fun_c_enc_fact` → `tab_pmtros.val_actfact` |
| RF-47 | El sistema debe crear líneas de detalle de factura con precio tomado de `tab_productos` | `fun_c_det_fact` |
| RF-48 | Cada línea de detalle debe registrar un movimiento de salida en `tab_kardex` | `fun_c_det_fact` → INSERT `tab_kardex` |
| RF-49 | El trigger `trg_kardex_actualizar_stock` debe descontar el stock de `tab_productos` al insertar en `tab_kardex` | DDL `tab_kardex.sql` |
| RF-50 | Si ningún detalle es válido, el encabezado de factura debe marcarse como anulado (`ind_estado = FALSE`) | `fun_facturar` |
| RF-51 | El proceso de facturación completo (enc_fact + det_fact + kardex) debe orquestarse desde `fun_facturar` | `fun_facturar` |

### Requerimientos No Funcionales

| ID | Descripción |
|----|-------------|
| RNF-13 | La facturación no debe modificar precios — los toma directamente de `tab_productos` en el momento de la venta |
| RNF-14 | Los aranceles están desactivados temporalmente (`val_bruto = val_neto`); la arquitectura debe permitir activarlos en el futuro |

---

## 9. Perfil de Usuario

### Requerimientos Funcionales

| ID | Descripción | Implementado en |
|----|-------------|-----------------|
| RF-52 | El usuario debe poder ver y editar su información personal (nombre, email, foto) | `perfil.php` |
| RF-53 | El usuario debe poder cambiar su contraseña | `perfil.php` |
| RF-54 | El usuario debe poder ver su historial de pedidos | `perfil.view.php` *(pendiente datos reales)* |
| RF-55 | El usuario debe poder ver y actualizar su dirección de envío guardada | `perfil.view.php` |
| RF-56 | El usuario debe poder subir/cambiar su foto de perfil | `perfil.php` → upload |
| RF-57 | El sistema debe validar el tamaño y formato de la imagen de perfil antes de guardarla | `perfil.php` |

### Requerimientos No Funcionales

| ID | Descripción |
|----|-------------|
| RNF-15 | Las imágenes de perfil deben almacenarse en el servidor con ruta relativa guardada en BD |

---

## 10. Mis Productos (Vendedor)

### Requerimientos Funcionales

| ID | Descripción | Implementado en |
|----|-------------|-----------------|
| RF-58 | El vendedor debe ver un listado de sus productos activos e inactivos | `mis_productos.php` |
| RF-59 | El vendedor debe poder crear un nuevo producto con nombre, descripción, precio, categoría, color, materia prima y oficio | `mis_productos/` → `fun_c_producto` |
| RF-60 | El vendedor debe poder editar los datos de un producto existente | `mis_productos/` → `fun_u_producto` |
| RF-61 | El vendedor debe poder activar/desactivar un producto | `mis_productos/` |
| RF-62 | El vendedor debe poder subir imágenes para sus productos | `mis_productos/` → upload |
| RF-63 | El sistema debe validar imágenes subidas (tamaño máximo, formato permitido) | `mis_productos/` |
| RF-64 | El vendedor debe poder gestionar el stock de sus productos | `mis_productos/` → `fun_kardex_mov` |
| RF-65 | Los movimientos de entrada de stock deben registrarse en `tab_kardex` con `tipo_movim = TRUE` | `fun_kardex_mov` |

### Requerimientos No Funcionales

| ID | Descripción |
|----|-------------|
| RNF-16 | Solo los usuarios con rol `vendedor` deben acceder al módulo de Mis Productos |

---

## 11. Registro de Vendedor

### Requerimientos Funcionales

| ID | Descripción | Implementado en |
|----|-------------|-----------------|
| RF-66 | El sistema debe permitir que un usuario registre su stand/productor | `registro_vendedor.php` |
| RF-67 | El formulario debe capturar: nombre del stand, descripción, slogan, oficio, logo, datos bancarios | `registro_vendedor.view.php` |
| RF-68 | Los datos del productor deben guardarse mediante `fun_c_productor` | `fun_c_productor.sql` |
| RF-69 | El campo `id_productor` debe ser INTEGER autoincremental | `tab_productores` |
| RF-70 | Los datos bancarios (banco, tipo de cuenta, número de cuenta) deben guardarse junto al productor | `fun_c_productor` → `tab_cuentas_prod` |

### Requerimientos No Funcionales

| ID | Descripción |
|----|-------------|
| RNF-17 | El logo del stand debe validarse en tamaño y formato antes de subirlo |

---

## Historial de Cambios

| Versión | Fecha | Cambio |
|---------|-------|--------|
| v1 | Inicial | Requerimientos originales en Excel |
| v2 | 2026-02-21 | Reescritura por módulos. Agrega facturación, kardex, ePayco, centralización de DB en `Database.php` |
