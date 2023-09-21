function openEditModal(element) {
    var parentDiv = $(element).closest('.card');
    var title = parentDiv.find('#title').text().trim();
    var description = parentDiv.find('#description').text().trim();

    var date = parentDiv.find('#dateCreated').text().trim();
    if (date != 'Не задано') {
        var [datePart, timePart] = date.split(' ');

        var [day, month, year] = datePart.split('.');
        var [hours, minutes] = timePart.split(':');

        const dateCreated = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}T${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}`;
        $('#editDatepicker').val(dateCreated);
    }
    $('#editTitle').val(title);
    $('#editDescription').val(description);
    $('#editID').val($(element).val());
}

$(document).ready(function () {
    $("#notesList div.card").filter(function () {
        $(this).toggle($(this).attr('class').toLowerCase().indexOf($("#buttonsFilter button.active").val()) > -1);
    });
    $(".buttonsFilter").on("click", function () {
        var btn = $(this);
        var value = $(this).val().toLowerCase();

        $("#notesList div.card").filter(function () {
            $(this).toggle($(this).attr("class").toLowerCase().indexOf(value) > -1);
        });

        $(".buttonsFilter").removeClass("active");
        btn.addClass("active");

    });
    $("#searchInput").on("input", function () {
        var value = $(this).val().toLowerCase();
        $("#notesList div.card").filter(function () {
            $(this).toggle(
                $(this).find("span.title").text().toLowerCase().indexOf(value) > -1 ||
                $(this).find("p.description").text().toLowerCase().indexOf(value) > -1
            );
        });
        $(".buttonsFilter").removeClass("active");
        $("#allButton").addClass("active");
    });

    $("#notesButtons").on("pjax:end", function () {
        $.pjax.reload({ container: "#notesList" });
    });

    theme();
});

$(document).on('ready pjax:end', function () {
    $("#notesList div.card").filter(function () {
        $(this).toggle($(this).attr('class').toLowerCase().indexOf($("#buttonsFilter button.active").val()) > -1);
    });
    
    theme();
});

async function removeNote(id) {
    let response = await fetch(`site/remove-notes?id=${id}`);
    if (response.ok) {
        $.pjax.reload("#notesList").done(function () {
            $("#notesList").prepend(`<div id="alert" class="alert-warning alert alert-dismissible" role="alert">

            <i class="bi bi-trash"></i> Заметка удалена
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            
            </div>`);
        });
    } else {
        console.log("Ошибка HTTP: " + response.status);
    }
}

function theme() {
    $('#btnSwitch').on("click", function () {
        if ($('html').attr('data-bs-theme') == 'light') {
            setTheme('dark');
        }
        else {
            setTheme('light');
        }
    })

    if (!$.cookie("theme")) {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            setTheme('dark');
        }
        else {
            setTheme('light');
        }
    } else {
        setTheme($.cookie("theme"));
    }
}

function setTheme(theme) {
    if (theme == 'dark') {
        $('html').attr('data-bs-theme', 'dark');
        $('#btnSwitch').html('<i class="bi bi-moon-stars-fill"></i>');
        $('#btnSwitch').addClass('btn-dark').removeClass('btn-light');
        $('#logout').addClass('btn-dark').removeClass('btn-light');
        $('.btnWidget').addClass('btn-outline-light').removeClass('btn-outline-dark');
    } else {
        $('html').attr('data-bs-theme', 'light');
        $('#btnSwitch').html('<i class="bi bi-sun-fill"></i>');
        $('#btnSwitch').addClass('btn-light').removeClass('btn-dark');
        $('#logout').addClass('btn-light').removeClass('btn-dark');
        $('.btnWidget').addClass('btn-outline-dark').removeClass('btn-outline-light');
    }
    $.cookie("theme", theme)
}