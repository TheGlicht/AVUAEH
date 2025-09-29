$(document).ready(function () {
    // === Mostrar datos del perfil ===
    $.getJSON("../../../resources/api/Docente/apiPerfil.php?action=mostrar", function (data) {
        if (data) {
            $("#nombre").val(data.nombreCompleto);
            $("#usuario").val(data.username);
            $(".col-md-12").append(
                `<input type="email" class="form-control" id="email" value="${data.email}" readonly>`
            );
        }
    });

    // === Guardar perfil ===
    $("#profileForm").on("submit", function (e) {
        e.preventDefault();
        $.post("../../../resources/api/Docente/apiPerfil.php?action=actualizar", {
            username: $("#usuario").val(),
            nombre: $("#nombre").val(), 
            email: $("#email").val()
        }, function (res) {
            let r = JSON.parse(res);
            if (r.success) {
                alert("Perfil actualizado correctamente");
            } else {
                alert("Error al actualizar");
            }
        });
    });

    // === Listar materias ===
    function cargarMaterias() {
        $.getJSON("../../../resources/api/Docente/apiPerfil.php?action=listar", function (data) {
            let tbody = $("#tablaMaterias tbody");
            tbody.empty();
            if (data.length === 0) {
                tbody.append(`<tr><td colspan="3">No hay materias registradas</td></tr>`);
            } else {
                data.forEach(m => {
                    tbody.append(`
                        <tr>
                            <td>${m.nombre} - Semestre ${m.semestre}</td>
                            <td>${m.grupo}</td>
                            <td>
                                <button class="btn btn-danger btn-sm eliminar" data-id="${m.id_relacion}">
                                    <i class="fa-solid fa-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    `);
                });
            }
        });
    }
    
    cargarMaterias();

   // === Agregar materia ===
$("#agregarMateria").on("click", function () {
    let id_materias = $("#materiaSelect").val();
    let grupo = $('#grupoInput').val(); // usar el input correcto

    if (!id_materias || !grupo) {
        alert("Seleccione una materia y escriba un grupo");
        return;
    }

    //  Validar duplicados en frontend
    let duplicado = false;
    $("#tablaMaterias tbody tr").each(function () {
        let materiaTexto = $(this).find("td").eq(0).text();
        let grupoTexto = $(this).find("td").eq(1).text();

        if (materiaTexto.includes($("#materiaSelect option:selected").text()) && grupoTexto == grupo) {
            duplicado = true;
            return false; // rompe el loop
        }
    });

    if (duplicado) {
        alert("Ya registraste esta materia con el mismo grupo.");
        return;
    }

    $.post("../../../resources/api/Docente/apiPerfil.php?action=agregar", 
        { id_materias: id_materias, grupo: grupo },  
        function (res) {
            let r = JSON.parse(res);
            if (r.success) {
                cargarMaterias();
                $('#grupoInput').val(""); 
            } else {
                alert(r.error || "Error al agregar materia");
            }
        }
    );
});


    // === Eliminar materia ===
    $(document).on("click", ".eliminar", function () {
        let id_relacion = $(this).data("id");
        if (confirm("¿Seguro que deseas eliminar esta materia?")) {
            $.post("../../../resources/api/Docente/apiPerfil.php?action=eliminar", { id_relacion }, function (res) {
                let r = JSON.parse(res);
                if (r.success) {
                    cargarMaterias();
                } else {
                    alert("Error al eliminar");
                }
            });
        }
    });
});    