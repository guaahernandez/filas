var tabla;

//funcion que se ejecuta al inicio
function init(){
   listar();
   cargaSedes();
   
   $("#sede_filter").change(function(){
       listar();
   });
}

//funcion listar
function listar(){
    var sede = $("#sede_filter").val();
    
    tabla=$('#tbllistado').dataTable({
        "aProcessing": true,//activamos el procedimiento del datatable
        "aServerSide": true,//paginacion y filrado realizados por el server
        dom: 'Bfrtip',//definimos los elementos del control de la tabla
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        responsive: true,
        buttons: [
                {
                extend: 'print',
                messageTop: 'Listado de Usuarios Logueados'
                },
                  'excelHtml5',
                  'pdf',      
                  'pageLength'   
        ],
        "language": {
                "url": "esp.json"
            },
        "ajax":
        {
            url:'../ajax/a_usuarios_logueados.php?op=listar',
            type: "POST",
            data: {sede: sede},
            dataType : "json",
            error:function(e){
                console.log(e.responseText);
            }
        },
        "bDestroy":true,
        "iDisplayLength":10,//paginacion
        "order":[[0,"desc"]]//ordenar (columna, orden)
    }).DataTable();
}

function cargaSedes(){
    $.post("../ajax/a_usuarios_logueados.php?op=selectSede",
        function(data)
        {
            $("#sede_filter").html(data);
            // Si hay opciones, seleccionar la primera y recargar la tabla
            if($("#sede_filter option").length > 0){
                $("#sede_filter").prop("selectedIndex", 0);
                listar(); 
            }
        });
}

function desloguear(nombc03){
    // Usamos confirmacion simple o sweetalert si esta disponible (usuario.js usa swal.fire)
    if(typeof swal !== 'undefined'){
        swal.fire({
            title: "¿Está seguro de desloguear a este usuario?",
            text: "¿Desea continuar?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Si, desloguear',
            cancelButtonText: `Cancelar`,
        })
        .then((result) => {
            if (result.isConfirmed) {
                ejecutarDeslogueo(nombc03);
            }
        });
    } else {
        if(confirm("¿Está seguro de desloguear a este usuario?")){
            ejecutarDeslogueo(nombc03);
        }
    }
}

function ejecutarDeslogueo(nombc03){
    $.post("../ajax/a_usuarios_logueados.php?op=desloguear", {nombc03 : nombc03}, function(e){
        //alert(e);
        if(typeof swal !== 'undefined'){
            swal.fire("Usuario deslogueado", "", "success");
        } else {
            alert(e);
        }
        tabla.ajax.reload();
    });
}

init();
