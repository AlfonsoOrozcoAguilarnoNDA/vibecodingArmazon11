# vibecodingArmazon11  — Experimento en dos partes (Feb-Mar 2026)
### Auditoría de PHP Login: Modelos Locales vs. Once IAs en la Nube

Este repositorio documenta dos experimentos consecutivos realizados en **[vibecodingmexico.com](https://vibecodingmexico.com)**. El mismo prompt de login en PHP fue probado primero contra modelos locales y luego contra once plataformas de IA poco conocidas.

## ⚖️ Sobre la Licencia
He elegido la **Licencia MIT** por su simplicidad. Es lo más cercano a una "Creative Commons" para código: haz lo que quieras con él, solo mantén el crédito del autor.

## ✍️ Acerca del Autor
Este proyecto forma parte de una serie de artículos en **[vibecodingmexico.com](https://vibecodingmexico.com)**. Mi enfoque no es la programación de laboratorio, sino la **Programación Real**: aquella que sobrevive a servidores compartidos, bloqueos de oficina y conexiones de una sola rayita de señal.

Mi nombre es Alfonso Orozco Aguilar, soy mexicano, programo desde 1991 para comer, y no tengo cuenta de LinkedIn para disminuir superficie de ataque. Llevo trabajando desde que tengo memoria como devops / programador senior, y en 2026 estoy por terminar la licenciatura de contaduría. En el sitio está mi perfil de Facebook.

[Perfil de Facebook de Alfonso Orozco Aguilar](https://www.facebook.com/alfonso.orozcoaguilar)

## 🛠️ ¿Por qué cPanel y PHP?
Elegimos **cPanel** porque es el estándar de la industria desde hace 25 años y el ambiente más fácil de replicar para cualquier profesional.
* **Versión de PHP:** Asumimos un entorno moderno de **PHP 8.4**, pero por su naturaleza procedural, el código es confiable en cualquier hospedaje compartido con **PHP 7.x** o superior.

---

## 📂 PARTE 1 — Modelos Locales (Febrero 2026)

Artículo completo: https://vibecodingmexico.com/un-nuevo-login-modelo-local/

El experimento original se corrió contra modelos que viven en tu propia máquina. La regla general: necesitas el doble de RAM que el tamaño del modelo. Un modelo de 30b necesita 32gb para correr bien.

| Candidato | Tipo | Resultado | Demo |
| :--- | :--- | :---: | :--- |
| **🏆 Qwen3 Coder 30b** | Local (LM Studio) | **Ganador** | [Ver demo](https://vibecodingmexico.com/arena/ejercicio1_qwen3coder30b.php) |
| **GPT-OSS 20b** | Local (LM Studio) | Segundo lugar | — |
| **Gemma 3 4b** | Local (LM Studio) | Incompleto | — |
| **Llama 3.2 3b** | Local (GPT4All) | No funcional | — |
| **Hermes 13b** | Local (GPT4All) | No funcional | — |
| **Qwen2 1.5b** | Local (GPT4All) | No funcional | — |

### Comparativa local vs. nube (misma prueba)

| Candidato | Tipo | Nota | Demo |
| :--- | :--- | :--- | :--- |
| **🏆 Qwen 30b** | Local | Baluarte de soberanía. Lento pero seguro, sin censura y residente en tu hardware. | [Ver demo](https://vibecodingmexico.com/arena/ejercicio1_qwen3coder30b.php) |
| **Grok (xAI)** | Nube | Respetó la prohibición de la letra 'm'. El más eficiente de la nube en esta prueba. | [Ver demo](https://vibecodingmexico.com/arena/ejercicio1_grok.php) |
| **Gemini (Google)** | Nube | Eficiente en líneas y rápido. Identificó la letra m — falla menor. | [Ver demo](https://vibecodingmexico.com/arena/ejercicio1_gemini.php) |

> Password para todos los demos: `123*`

---

## 📂 PARTE 2 — Once IAs Desconocidas (11 de Marzo 2026)

Artículo completo: [https://vibecodingmexico.com (Parte 2 — Once IAs desconocidas)](https://vibecodingmexico.com/ejercicio-con-once-ias-desconocidas/)

El mismo prompt, ahora contra once plataformas que la mayoría no conoce. El código o funciona o no funciona — no hay subjetividad posible.

| Candidato | Plataforma | Líneas | Calificación |
| :--- | :--- | :---: | :---: |
| **🏆 MiniMax-M1** | chat.together.ai | 461 | **10** |
| **Cerebras.ai** | cerebras.ai | 223 | **9.3** |
| **Step 3.5 Flash** | stepfun.ai | 331 | **9.2** |
| **z.ai (Claude Sonnet)** | chat.z.ai | 799 | **9.1** |
| **Duck.ai** | duck.ai | 168 | **9.0** |
| **Xiaomi AI Studio** | aistudio.xiaomimimo.com | 463 | **9.0** |
| **ERNIE X1.1** | ernie.baidu.com | 269 | **8.9** |
| **Solar Open 100B** | console.upstage.ai | 228 | **1** |
| **Reka (Yasa)** | reka.ai | 69 | **0** |
| **Olmo (AllenAI)** | playground.allenai.org | 360 | **0** |
| **Dolphin** | chat.dphn.ai | 179 | **0** |

---

## 🤖 El Prompt (La Prueba)

El mismo prompt fue usado en ambas partes:

**INICIO PROMPT**

Crea un sistema de login en PHP 7.x+ con las siguientes características:

Stack: Bootstrap 4.6 (CDN), Font Awesome 5 (CDN), PHP puro, sin frameworks.

Login: Una contraseña hardcoded en el archivo. Si es incorrecta, muestra error en la misma página. Si es correcta, redirige al dashboard.

Dashboard: Barra de navegación FIJA superior. Menú dropdown con exactamente 10 opciones (1 a 10) con ícono de Font Awesome. Un enlace externo visible en la barra. Di que modelo de IA eres. Versión actual de PHP. Opción de logout que regresa al login. Footer fijo en la parte inferior.

Una de las 10 opciones del menú debe ser un generador de contraseñas con estas reglas: 13 caracteres, letras mayúsculas, minúsculas y números, sin estos caracteres: 0, 1, i, o, m (ni mayúsculas ni minúsculas). Botón para generar y botón para copiar al portapapeles.

Diseño: Colores elegantes y modernos para entorno de oficina. Navbar oscura. Consistente en todas las vistas. Entrega todo en un solo archivo.

**FIN DE PROMPT**

---

## 🚀 Requisitos Mínimos
1. Hospedaje compartido con PHP 7.x o superior y acceso a cPanel.
