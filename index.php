<!DOCTYPE html>
<html lang="es_MX">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test SOAP</title>
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.51.1/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<style>
    body {
        padding: 1cm;
    }

    .header {
        margin-bottom: 1cm;
    }

    .content {
        margin-bottom: 1cm;
    }

    .footer {
        border: 1px solid red;
    }

    hr {
        width: 100%;
    }

    .mdi {
        font-size: 1.2rem;
    }

    .border-test {
        border: 1px solid red;
    }

    pre {
        white-space: pre-wrap;
        background-color: black;
        padding: 15px;
        border-radius: 15px;
    }
</style>

<body>
    <div class="header">
        <div class="card bg-neutral shadow-xl text-white">
            <div class="card-body items-center">
                <h2 class="card-title">
                    <span>Prueba de SOAP Client</span>
                    <a href="#modal-info" class="mdi mdi-information"></a>
                </h2>
            </div>
        </div>

        <!-- Modal Info -->
        <div class="modal text-white" id="modal-info">
            <div class="modal-box">
                <h3 class="font-bold text-lg">
                    <span class="mdi mdi-information"></span>
                    <span>Información</span>
                </h3>
                <hr class=" my-4">
                <ul>
                    <li>
                        IP Address (Server): <strong><?= $_SERVER['SERVER_ADDR'] ?></strong>
                    </li>
                    <li>
                        PHP Version: <strong><?= phpversion() . ' (' . php_uname('m') . ')' ?></strong>
                    </li>
                    <li>
                        Is SOAP Client Enabled: <strong><?= extension_loaded('soap') ? 'YES' : 'NO' ?></strong>
                    </li>
                </ul>
                <div class="modal-action">
                    <a href="#" class="btn btn-sm btn-outline btn-error">
                        <span>Cerrar</span>
                        <span class="mdi mdi-close-thick ml-2"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="grid grid-cols-3 gap-10">

            <div class="col-span-1">
                <div class="card bg-neutral shadow-xl text-white">
                    <div class="card-body">
                        <h2 class="card-title">
                            <span class="mdi mdi-test-tube mr-2"></span>
                            <span>Iniciar</span>
                        </h2>
                        <hr class="my-4">
                        <p class="text-justify">
                            Da clic en el botón de abajo para ejecutar la prueba
                            de conexión al servicio SOAP.
                        </p>
                        <form onsubmit="openModalQuestion()">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Ingresa un WSDL: *</span>
                                </label>
                                <input type="url" name="wsdl" placeholder="WSDL" class="input" required />
                            </div>
                            <div class="text-right mt-5">
                                <button type="submit" class="btn btn-primary">
                                    Probar <span class="mdi mdi-send ml-2"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-span-2">
                <div class="card shadow-xl bg-neutral text-white">
                    <div class="card-body">
                        <h2 class="card-title">
                            <span class="mdi mdi-server-minus mr-2"></span>
                            <span>Respuesta del servidor</span>
                            <button class="mdi mdi-refresh-circle ml-1" onclick="cleanPre()"></button>
                        </h2>
                        <hr class="my-4">
                        <pre id="respuestaServer" class="text-success"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    /**
     * Textos por defecto
     */
    let textDefaultWsdl = 'http://www.dneonline.com/calculator.asmx?WSDL';
    let textDefaultPre = 'Aquí se mostrará la respuesta del servidor';

    /**
     * Elementos
     */
    let inputWsdl = document.querySelector('input[name="wsdl"]');
    let preRespuestaServer = document.querySelector('#respuestaServer');


    /**
     * On windows load
     */
    window.onload = function() {
        //Asignar valor por defecto a los elementos
        inputWsdl.value = textDefaultWsdl;
        preRespuestaServer.innerText = textDefaultPre;
    }

    /**
     * Clean pre
     */
    function cleanPre() {
        preRespuestaServer.innerText = textDefaultPre;
    }

    /**
     * Open sweet alert modal question
     */
    function openModalQuestion() {
        event.preventDefault();
        Swal.fire({
            title: '¿Deseas continuar?',
            text: 'No se podrá detener la ejecución del proceso',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                testSoapClient();
            }
        })
    }

    /**
     * Test SOAP Client
     */
    async function testSoapClient() {
        Swal.fire({
            title: 'Procesando...',
            text: 'Por favor espere...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const RESPONSE = await axios.get(`/test.php`, {
                params: {
                    wsdl: inputWsdl.value
                }
            });

            preRespuestaServer.innerText = JSON.stringify(RESPONSE.data, null, 2);
        } catch (err) {
            let print = err?.response?.data || err?.message || err;
            preRespuestaServer.innerText = JSON.stringify(print, null, 2);
        }

        Swal.close();
    }
</script>

</html>