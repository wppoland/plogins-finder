=== Plogins Finder - Product Finder Quiz for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, product finder, product quiz, product recommendation, guided selling
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Un amable cuestionario buscador de productos paso a paso para WooCommerce: las preguntas guiadas de elección única llevan a cada cliente a una única recomendación de producto. Sin jQuery.

== Description ==

Finder añade un cuestionario guiado del tipo «ayúdame a elegir» a cualquier página mediante el shortcode `[finder]`. Los clientes responden a una breve serie de preguntas de elección única y ven un producto recomendado, con su imagen, su precio y un botón directo a la página del producto.

Finder se desarrolla de forma abierta (código abierto). Encontrarás el código y un lugar para informar de errores o proponer funciones en https://github.com/wppoland/plogins-finder.

Tú defines las preguntas y las opciones y luego asignas un producto a cada combinación de respuestas. El botón «Generar combinaciones» crea el mapa a partir de tus pasos, así que solo tienes que elegir un producto para cada ruta.

= Documentation and links =

* <strong>Documentación</strong> - https://plogins.com/es/plogins-finder/docs/
* <strong>Página del plugin</strong> - https://plogins.com/es/plogins-finder/
* <strong>Código fuente</strong> - https://github.com/wppoland/plogins-finder
* <strong>Informes de errores y peticiones de funciones</strong> - https://github.com/wppoland/plogins-finder/issues

= Built for speed and accessibility =

* <strong>Sin jQuery</strong> en el código del frontend del propio plugin: el script es JavaScript puro, diferido y cargado en el pie de página.
* <strong>Sin saltos de diseño (CLS).</strong> Se reserva espacio para cada paso y para la tarjeta de resultado, de modo que avanzar nunca redistribuye la página. La imagen de la recomendación se prioriza para un LCP rápido.
* <strong>Resultado con REST.</strong> La recomendación se obtiene de un endpoint REST ligero (no admin-ajax), se lee en directo para que el precio y el stock estén siempre actualizados, y nunca se almacena en caché.
* <strong>Compatible con el teclado.</strong> Grupos de radio reales, una barra de progreso en directo, estilos de foco visibles, un botón Atrás y manejo completo con el teclado.
* <strong>Se puede reanudar y compartir.</strong> El paso actual y las respuestas se guardan en la URL y en el almacenamiento de la pestaña, así que al recargar no se pierde el avance y un enlace con todas las respuestas se abre directamente en el resultado.

= Settings =

Una página de ajustes (menú «Finder», con permisos de WooCommerce) te permite:

* Activar o desactivar el cuestionario, definir el encabezado, el subtítulo y el color de acento, y elegir si el precio aparece en la tarjeta de resultado.
* Construir los pasos: cada paso es una pregunta con opciones de elección única; el «valor» de la opción es un slug corto que identifica la respuesta.
* Asignar resultados: genera una fila por cada combinación de respuestas a partir de tus pasos y luego elige el producto recomendado para cada una, con encabezado, descripción y etiqueta de botón opcionales. Un aviso claro muestra de un vistazo cuántas combinaciones todavía necesitan un producto.
* Definir un producto alternativo que se muestra cuando ninguna combinación coincide o cuando un producto asignado no está disponible.

= Translation ready =

Todas las cadenas se pueden traducir mediante el dominio de texto `plogins-finder`, y en el directorio `/languages` se incluye una plantilla `plogins-finder.pot`. Al borrar el plugin se eliminan sus opciones.

= How it works =

El cuestionario se renderiza en el servidor (para que pueda cachearse junto con la página) y el avance entre pasos ocurre por completo en el navegador, sin ida y vuelta al servidor. Solo se obtiene la recomendación final, mediante una petición REST del mismo origen a tu propio sitio, y se renderiza al momento para que el precio y el stock estén actualizados. El CSS y el JavaScript se cargan únicamente en las páginas que contienen el shortcode `[finder]`.

== Installation ==

1. Sube el plugin a `/wp-content/plugins/finder` o instálalo desde Plugins → Añadir nuevo.
2. Actívalo. WooCommerce debe estar activo.
3. Entra en el menú <strong>Finder</strong> del escritorio, construye tus pasos, genera las combinaciones y elige un producto para cada una, y luego activa el cuestionario.
4. Añade el cuestionario a cualquier página o entrada con el shortcode `[finder]`.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Sí. Finder requiere una instalación activa de WooCommerce.

= Does it use jQuery? =

No. El script del frontend del propio plugin es JavaScript puro, sin dependencia de jQuery.

= Where do I put the quiz? =

En cualquier sitio donde funcione el shortcode `[finder]`: una página, una entrada o un bloque de HTML personalizado / shortcode de un maquetador.

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
3. La pantalla de ajustes de Finder: editor de pasos y mapa de resultados.

== External Services ==

Finder no se conecta ni envía ningún dato a ningún servicio externo o servidor de terceros. No incluye ningún SDK, cliente de API, fuente web, tesela de mapa, recurso de CDN ni llamada de analítica: todo se ejecuta en tu propio sitio.

Todos los datos permanecen dentro de tu base de datos de WordPress: las preguntas, las opciones, el mapa de resultados y los ajustes se guardan en la opción `finder_settings` (con `finder_db_version` para el seguimiento del esquema). Cuando un cliente termina el cuestionario, sus respuestas se envían en una petición REST del mismo origen al endpoint `/wp-json/` de tu sitio, que devuelve la recomendación; nunca se realiza ninguna petición HTTP saliente. El paso actual y las respuestas también se guardan en la URL de la página y en el almacenamiento de sesión de la pestaña del navegador para que el cuestionario se pueda reanudar. Al borrar el plugin se eliminan sus opciones.

== Changelog ==

= 1.0.2 =
* Primera versión estable: cuestionario buscador de productos guiado paso a paso con una zona de administración de pasos y resultados sencilla, recomendación en directo basada en REST, frontend accesible en JavaScript puro y cero saltos de diseño. Indicadores de opción de estilo radio, movimiento sutil (respeta la reducción de movimiento) y traducción al polaco.
