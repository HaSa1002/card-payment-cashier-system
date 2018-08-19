$(document).ready(function() {;
    $('#addWare').submit(function(event) {
        // Get the data
        var data = $("#addWare").serializeArray();
        if (data[1].value == "") {
            data[1].value = 1;
        }
		// Add the data to table
		$("tbody").append("<tr class=\""+data[0].value+"\"><th>"+data[0].value+"</th><td>Verarbeite Daten...</td><td>"+data[1].value+"</td><td>---</td><td>Bitte Warten</td></tr>");
        //Reset form
        $('form').trigger("reset");

        //Delete Alerts or w-rong old input
        $("div.alert").remove();
        $("tr.delete").remove();

        event.preventDefault();
        $.post('/kassensystem/cashier/addWare', data, function(data, t, jq) {
            
			$("."+data.id).remove();
            $("tbody").append("<tr class=\""+data.id+"\"><th>"+data.id+"</th><td>"+data.name+"</td><td>"+data.menge+"</td><td>"+data.price+"</td><td><a href=\"/kassensystem/cashier/delete/"+data.id+"/1/6\" class=\"btn btn-sm btn-outline-danger\">Löschen</a></td></tr>");
            let sum = parseFloat($("h4").text().substr(7).replace(',', '.'));
            sum += data.addToSum;
            sum = sum.toFixed(2);
            $("h4").text("Summe: "+ sum.toString().replace('.', ','));
        }, "JSON") .fail (function(d, t, jq) {
            if (jq == "Not acceptable") //Id is non-existent
                $("."+data[0].value).replaceWith("<tr class=\"delete\"><th>"+data[0].value+"</th><td>Die Warenid ist nicht vorhanden</td><td>---</td><td>---</td><td></td></tr>");
            else //Server errror
                $("."+data[0].value).replaceWith("<tr class=\"delete\"><th>"+data[0].value+"</th><td>Serverfehler. Bitte nochmal versuchen</td><td>---</td><td>---</td><td></td></tr>");
        })
        
    })
    $('#delete').submit(function(event) {
        // Get the data
        var data = $("#delete").serializeArray();
        $('#delete').trigger("reset");

        //Delete Alerts or wrong old input
        $("div.alert").remove();
        $("tr.delete").remove();
        // Rename the row, to tell the user that we are deleting the row
        if (!$("."+data[0].value).length) {
            $(".modal-body").prepend("<div class=\"alert-danger al delete\">Die Ware ("+data[0].value+") konnte nicht gelöscht werden.</div>");
            $(".al").addClass('alert');
            $(".al").removeClass('al');
            event.preventDefault();
            return;
        }
		$("."+data[0].value).replaceWith("<tr class=\""+data[0].value+"\"><th>"+data[0].value+"</th><td>Die Ware wird gelöscht</td><td>---</td><td>---</td><td>Bitte Warten</td></tr>");
        
        $("#delete").modal('hide');
        event.preventDefault();
        $.post('/kassensystem/cashier/delete', data, function(data, t, jq) {
            console.log(data);
			$("."+data.id).remove();
            let sum = parseFloat($("h4").text().substr(7).replace(',', '.'));
            sum -= data.removeFromSum;
            sum = sum.toFixed(2);
            $("h4").text("Summe: "+ sum.toString().replace('.', ','));
            
        }, "JSON") .fail (function(d, t, jq) {
            console.log(d);
            if (jq == "Not acceptable") //Id is non-existent
                $("."+data[0].value).replaceWith("<tr class=\"delete\"><th>"+data[0].value+"</th><td>Die Warenid konnte nicht gelöscht werden</td><td>---</td><td>---</td><td></td></tr>");
            else //Server errror
                $("."+data[0].value).replaceWith("<tr class=\"delete\"><th>"+data[0].value+"</th><td>Serverfehler. Bitte nochmal versuchen</td><td>---</td><td>---</td><td></td></tr>");
        })
        
    })
})