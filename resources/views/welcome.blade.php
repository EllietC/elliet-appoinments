<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Integrate Bootstrap Datepicker in Laravel </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
</head>

<body>
    <div class="row" style="margin-right: 30px; margin-left: 30px;">
        <div class="col-md-6">
            <h2 class="mb-4">Dancing with Death</h2>
            <div>Seleccione una fecha para ver las horas disponibles</div>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker'>
                    <input type='text' class="form-control" id="date" value="Ejemplo" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <br>
                <button type="submit" class="btn btn-default btn-primary consulta">Consultar</button>
            </div>
        </div>
        <div class="col-md-6 table-date" style="margin-top:70px; display:none">
            <label class="col-md-4">Seleccione una hora<br>para agendar</label>
            <div class="col-md-8">
                <table id="tblOne">
                    <tbody>
                        <tr>
                            <td class="link-add">
                                Prueba
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>


</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    $(function() {
        $('#datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });

    $('.consulta').click(function() {
        dates = ['09', '10', '11', '12', '13', '14', '15', '16', '17', '18'];
        formatted_dates = ['09:00 AM', '10:00 AM', '11:00 AM', '12:00 AM', '13:00 PM', '14:00 PM', '15:00 PM', '16:00 PM', '17:00 PM', '18:00 PM'];
        date = $("#datetimepicker").find("input").val();
        day = new Date(date).getDay();
        console.log(day);
        if (day != 5 && day != 6) {
            console.log(date);
            var tbody = $('#tblOne tbody');
            tbody.empty();
            var request = $.ajax({
                url: "http://elliet.oxus.cl/api/appoinments/" + date,
                type: "GET"
            });
            request.done(function(data) {
                $('.table-date').css('display', 'flex');
                console.log(data['data']);
                var rowLength = data['data'].length;
                for (var i = 0; i < rowLength; i += 1) {
                    var row = data['data'][i];
                    hora = row['date'].split(' ');
                    hora = hora[1].split(':', 1)
                    var a = dates.indexOf(hora[0]);
                    dates.splice(a, 1);
                    formatted_dates.splice(a, 1);
                }
                for (var i = 0; i < formatted_dates.length; i += 1) {
                    var tr = $('<tr/>',{ class: 'link-add', value: date});
                    tr.append($('<td/>').append(formatted_dates[i]));
                    tbody.append(tr);
                }

            });
            request.fail(function(error) {
                console.log('Error: ', error);
                return window.alert('Ya existe un Cita para esta hora');
            });
        } else {
            $('.table-date').css('display', 'none');
            return window.alert('!Solo se realizan citas de Lunes a Viernes¡');
        }
    });
    $(document).on('click', '.link-add', function(){
        time = ($(this).text()).split(':', 1);
        date = $(this).attr('value');
        new_time = date + ' ' + time + ':00:00';
        data = "InputJSON:" +  JSON.stringify({ email: "carreno@gmail.com", date:new_time});
        $body={'email':'hola@gmail.com',
                'date':new_time}
        var request = $.ajax({
                data: $body,
                url: "http://elliet.oxus.cl/api/appoinments",
                type: "POST"
            });
            request.done(function(data) {
                console.log(request);
                window.alert(data['message']);
                $('.table-date').css('display', 'none');
            });
            request.fail(function(error) {
                console.log('Error: ', error);
            });
    });
</script>
<style>
    #tblOne {
        width: 23%;
        border: 1px solid #000;
        background-color: #eeeeee;

    }

    tr,
    td {
        text-align: left;
        vertical-align: top;
        border: 1px solid #000;
        border-collapse: collapse;
        padding: 0.8em;
        caption-side: bottom;
    }

    caption {
        padding: 0.3em;
    }

    tr:hover {
        background: #d6d6d6;
        cursor: pointer;
    }
</style>