 document.addEventListener("DOMContentLoaded", function () {
            $('#monthYearPicker').datepicker({
                format: "mm/yyyy",
                startView: "months",
                minViewMode: "months"
            });

            $('#archive-link').on('click', function (e) {
                e.preventDefault();
                var myModal = new bootstrap.Modal(document.getElementById('archiveModal'));
                myModal.show();
            });
        });