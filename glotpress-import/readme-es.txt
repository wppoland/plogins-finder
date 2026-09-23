=== Elektilo - Product Finder Quiz for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, product finder, product quiz, product recommendation, guided selling
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 8.1
Stable tag: 1.1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Un amable cuestionario buscador de productos paso a paso para WooCommerce: las preguntas guiadas de elección única llevan a cada cliente a una única recomendación de producto. Sin jQuery.

== Description ==

Elektilo añade un cuestionario guiado del tipo «ayúdame a elegir» a cualquier página mediante el shortcode `[elektilo]`. Los clientes responden a una breve serie de preguntas de elección única y ven un producto recomendado, con su imagen, su precio y un botón directo a la página del producto.

Elektilo se desarrolla de forma abierta (código abierto). Encontrarás el código y un lugar para informar de errores o proponer funciones en [github.com/wppoland/plogins-finder](https://github.com/wppoland/plogins-finder).

Tú defines las preguntas y las opciones y luego asignas un producto a cada combinación de respuestas. El botón «Generar combinaciones» crea el mapa a partir de tus pasos, así que solo tienes que elegir un producto para cada ruta.

= Documentation and links =

* **Documentación**: [plogins.com/es/plogins-finder/docs/](https://plogins.com/es/plogins-finder/docs/)
* **Página del plugin**: [plogins.com/es/plogins-finder/](https://plogins.com/es/plogins-finder/)
* **Código fuente**: [github.com/wppoland/plogins-finder](https://github.com/wppoland/plogins-finder)
* **Informes de errores y peticiones de funciones**: [github.com/wppoland/plogins-finder/issues](https://github.com/wppoland/plogins-finder/issues)

= Built for speed and accessibility =

* **Sin jQuery** en el código del frontend del propio plugin: el script es JavaScript puro, diferido y cargado en el pie de página.
* **Sin saltos de diseño (CLS).** Se reserva espacio para cada paso y para la tarjeta de resultado, de modo que avanzar nunca redistribuye la página. La imagen de la recomendación se prioriza para un LCP rápido.
* **Resultado con REST.** La recomendación se obtiene de un endpoint REST ligero (no admin-ajax), se lee en directo para que el precio y el stock estén siempre actualizados, y nunca se almacena en caché.
* **Compatible con el teclado.** Grupos de radio reales, una barra de progreso en directo, estilos de foco visibles, un botón Atrás y manejo completo con el teclado.
* **Se puede reanudar y compartir.** El paso actual y las respuestas se guardan en la URL y en el almacenamiento de la pestaña, así que al recargar no se pierde el avance y un enlace con todas las respuestas se abre directamente en el resultado.

= Settings =

Una página de ajustes (menú «Elektilo», con permisos de WooCommerce) te permite:

* Activar o desactivar el cuestionario, definir el encabezado, el subtítulo y el color de acento, y elegir si el precio aparece en la tarjeta de resultado.
* Construir los pasos: cada paso es una pregunta con opciones de elección única; el «valor» de la opción es un slug corto que identifica la respuesta.
* Asignar resultados: genera una fila por cada combinación de respuestas a partir de tus pasos y luego elige el producto recomendado para cada una, con encabezado, descripción y etiqueta de botón opcionales. Un aviso claro muestra de un vistazo cuántas combinaciones todavía necesitan un producto.
* Definir un producto alternativo que se muestra cuando ninguna combinación coincide o cuando un producto asignado no está disponible.

= Translation ready =

Todas las cadenas se pueden traducir mediante el dominio de texto `elektilo`, y en el directorio `/languages` se incluye una plantilla `elektilo.pot`. Al borrar el plugin se eliminan sus opciones.

= How it works =

El cuestionario se renderiza en el servidor (para que pueda cachearse junto con la página) y el avance entre pasos ocurre por completo en el navegador, sin ida y vuelta al servidor. Solo se obtiene la recomendación final, mediante una petición REST del mismo origen a tu propio sitio, y se renderiza al momento para que el precio y el stock estén actualizados. El CSS y el JavaScript se cargan únicamente en las páginas que contienen el shortcode `[elektilo]`.

== Installation ==

1. Sube el plugin a `/wp-content/plugins/elektilo` o instálalo desde Plugins > Añadir nuevo.
2. Actívalo. WooCommerce debe estar activo.
3. Entra en el menú **Elektilo** del escritorio, construye tus pasos, genera las combinaciones y elige un producto para cada una, y luego activa el cuestionario.
4. Añade el cuestionario a cualquier página o entrada con el shortcode `[elektilo]`.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Sí. Elektilo requiere una instalación activa de WooCommerce.

= Does it use jQuery? =

No. El script del frontend del propio plugin es JavaScript puro, sin dependencia de jQuery.

= Where do I put the quiz? =

En cualquier sitio donde funcione el shortcode `[elektilo]`: una página, una entrada o un bloque de HTML personalizado / shortcode de un maquetador.

= How many questions can I ask? =

Tantos pasos como quieras, cada uno con tantas opciones de elección única como quieras. Dos o tres pasos cortos convierten mejor.

= What happens at the end? =

El cliente ve un producto recomendado: su imagen, un precio opcional, un encabezado y una descripción opcionales y un botón hacia la página del producto. Si ninguna combinación coincide o el producto asignado no está disponible, se muestra tu producto alternativo.

= Is the price on the result always up to date? =

Sí. La recomendación se obtiene en directo por REST en cada petición y nunca se almacena en caché, así que el precio y el stock reflejan tu tienda en ese momento.

= Does this plugin work on WordPress Multisite? =

Sí. Actívalo en toda la red o en sitios concretos; cada sitio conserva sus propios ajustes.

== Screenshots ==

1. El cuestionario buscador paso a paso en la tienda.
2. La tarjeta de recomendación con el producto y una llamada a la acción.
3. La pantalla de ajustes de Elektilo: editor de pasos y mapa de resultados.

== External Services ==

Elektilo no se conecta ni envía ningún dato a ningún servicio externo o servidor de terceros. No incluye ningún SDK, cliente de API, fuente web, tesela de mapa, recurso de CDN ni llamada de analítica: todo se ejecuta en tu propio sitio.

Todos los datos permanecen dentro de tu base de datos de WordPress: las preguntas, las opciones, el mapa de resultados y los ajustes se guardan en la opción `finder_settings` (con `finder_db_version` para el seguimiento del esquema). Cuando un cliente termina el cuestionario, sus respuestas se envían en una petición REST del mismo origen al endpoint `/wp-json/` de tu sitio, que devuelve la recomendación; nunca se realiza ninguna petición HTTP saliente. El paso actual y las respuestas también se guardan en la URL de la página y en el almacenamiento de sesión de la pestaña del navegador para que el cuestionario se pueda reanudar. Al borrar el plugin se eliminan sus opciones.

== Changelog ==

= 1.1.2 =
* Ahora se llama Elektilo. El equipo de revisión de WordPress.org pide que el nombre de un plugin empiece por un identificador distintivo e inventado, y no por una palabra descriptiva genérica. Elektilo es esperanto para una herramienta que elige, que es justo lo que hace el cuestionario. El dominio de texto sigue al nombre; los datos guardados, los ajustes y todos los hooks quedan intactos.
* El shortcode ahora es `[elektilo]`. Antes era `[finder]`, una etiqueta lo bastante genérica como para que cualquier otro plugin la reclamara primero. Esta es la última versión en la que podía cambiar sin romper páginas existentes, porque el plugin aún no se ha publicado.
* El menú del escritorio muestra Elektilo en lugar de Product Finder, y se han corregido los lugares en los que la documentación seguía diciendo Finder.

= 1.0.10 =
* Corregido: los glifos de flecha en las rutas del menú del escritorio y en las cadenas que se entregan a quienes traducen. Una flecha dentro de una cadena traducible convierte ese glifo en un problema para cada traducción y cambia el diseño en cualquier idioma que la pierda.

= 1.0.9 =
* La descripción corta tenía 151 caracteres, uno más que el límite que permite WordPress.org, así que el directorio de plugins la cortaba a mitad de frase. Acortada en una palabra; el significado no cambia.

= 1.0.8 =
* Se regeneró la plantilla de traducción. Todavía nombraba una versión anterior del plugin y apuntaba a líneas de código que se habían movido, que es lo que leen las herramientas de traducción para mostrar una cadena en su contexto.

= 1.0.7 =
* Renombrado a Plogins Finder - Product Finder Quiz for WooCommerce para que el nombre empiece por la marca y no por una palabra genérica, que es lo que pide el equipo de revisión de plugins de WordPress.org. El slug del plugin no cambia.

= 1.0.6 =
* Probado con WordPress 7.1. Verificado activando esta versión en una instalación limpia de 7.1 con WooCommerce 11.1, no editando la cabecera.

= 1.0.5 =
* Mejoras de accesibilidad en el marcado del escritorio y de la tienda.

= 1.0.4 =
* Corregido: en escritorio la página ya no se desplaza hasta el buscador al cargar (el foco usa ahora preventScroll).
* Corregido: la URL se mantiene limpia en la primera visita, el estado del cuestionario (?fa=&fs=) solo se escribe después de que la persona responda.

= 1.0.3 =
* Se añadió la opción «Abrir en una pestaña nueva», para que el producto recomendado se abra en una pestaña nueva del navegador.

= 1.0.2 =
* Primera versión estable: cuestionario buscador de productos guiado paso a paso con una zona de administración de pasos y resultados sencilla, recomendación en directo basada en REST, frontend accesible en JavaScript puro y cero saltos de diseño. Indicadores de opción de estilo radio, movimiento sutil (respeta la reducción de movimiento) y traducción al polaco.
