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
                tbody.append(`<tr><td colspan="2">No hay materias registradas</td></tr>`);
            } else {
                data.forEach(m => {
                    tbody.append(`
                        <tr>
                            <td>${m.nombre} - Semestre ${m.semestre}</td>
                            <td>
                                <button class="btn btn-danger btn-sm eliminar" data-id="${m.id_relacion}" data-materia="${m.id_materias}">
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
        if (!id_materias) {
            alert("Seleccione una materia");
            return;
        }
        $.post("../../../resources/api/Docente/apiPerfil.php?action=agregar", { id_materias }, function (res) {
            let r = JSON.parse(res);
            if (r.success) {
                cargarMaterias();
            } else {
                alert("Error al agregar materia");
            }
        });
    });

    // === Eliminar materia ===
    $(document).on("click", ".eliminar", function () {
        let id_materias = $(this).data("materia");
        if (confirm("¿Seguro que deseas eliminar esta materia?")) {
            $.post("../../../resources/api/Docente/apiPerfil.php?action=eliminar", { id_materias }, function (res) {
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
