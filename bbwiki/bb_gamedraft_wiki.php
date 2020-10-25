<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        form  { display: table; width: 60% }
        p     { display: table-row-group; line-height: 200% }
        label { display: table-cell; width: 20%;}
        input, select { display: table-cell; padding: 0.3em;}
        .form_section { color: #c41233; line-height: 200%};

    </style>

    <script>

        function completeazaParticipanti()
        {
            var listaParticipanti = getListaParticipanti();
            document.getElementById("nrParticipanti").value = listaParticipanti.length;
            creazaTabelListe();
        }

        function creazaTabelListe()
        {
            var boxContainer = document.getElementById('paraDescrieri');
            var currNoPart   = document.getElementById("nrParticipanti").value;

            // remove boxes
            if (currNoPart < boxContainer.childElementCount-1) {
                while (boxContainer.childElementCount-1 > currNoPart) {
                    boxContainer.removeChild(boxContainer.lastChild);
                }
            // add boxes
            } else {
                var listaParticipanti = getListaParticipanti();

                // update existing boxes
                if (listaParticipanti !== undefined) {
                    for (var i = 1; i < boxContainer.childElementCount; i++) {
                        var nume = listaParticipanti[i-1];
                        boxContainer.children[i].placeholder = "lista " + (nume === undefined ? "" : nume.trim());
                    }
                }

                // add new boxes
                while (currNoPart > boxContainer.childElementCount-1) {
                    var currentIndex = boxContainer.childElementCount-1;
                    var input = document.createElement("textarea");
                    input.name = "descriere" + currentIndex;
                    input.rows = 10;
                    input.placeholder = "lista ";
                    if (currentIndex <listaParticipanti.length) {
                        input.placeholder += listaParticipanti[currentIndex].trim();
                    }
                    document.getElementById("paraDescrieri").appendChild(input);
                }
            }
        }

        function getListaParticipanti()
        {
            var listaParticipanti = document.getElementById("lista_participanti").value;
            listaParticipanti = listaParticipanti.replace(/;/g, ",");
            listaParticipanti = listaParticipanti.split(",");
            return listaParticipanti;
        }

    </script>
</head>

<body>
<form method = "POST" action = "bb_gamedraft_wiki_convert.php">
    <p><strong class = "form_section">Informaţii generale</strong></p>
    <p><label>Tip</label>
        <select name = "tip_draft">
            <option value = "game">Game Draft</option>
            <option value = "video">Video Draft</option>
            <option value = "music">Music Draft</option>
        </select>
    <p><label>Nume:</label><input type="text" placeholder = "numele draftului" name = "nume_draft"></p>
    <p><label>Descriere:</label><textarea placeholder = "despre ce e vorba in draft" name = "descriere""></textarea></p>
    <p><label>Perioada:</label><input type="text" name = "perioada"></p>
    <p><label>Linkuri:</label><input type="text" placeholder = "thread general/discutii" name = "link_discutii"><input type="text" placeholder = "thread alegeri" name = "link_alegeri"></p>

    <p><label>Alt thread relevant:</label><input type="text" placeholder = "nume thread" name = "link_other_name"><input type="text" placeholder = "url" name = "link_other_url"></p>

    <p><label>Reguli:</label><input type="text" name = "reguli"></p>
    <p><label>Game master :</label> <input type="text" placeholder = "cine a organizat" name = "organizator"></p>

    <p><strong class = "form_section">Participanţi şi liste</strong></p>
    <p><label>Castigator:</label><input type="text" placeholder = "cine a castigat" name = "castigator"></p>
    <p><label>Clasament:</label><textarea placeholder = "clasament final" name = "clasament"></textarea></p>
    <p><label>Lista participanti:</label><textarea rows = "3" id = "lista_participanti" placeholder = "nume1, nume2, nume3"  name = "lista_participanti" oninput = "completeazaParticipanti()"></textarea></p>
    <p><label>Numar participanti:</label><input type="number" name = "nr_participanti" id = "nrParticipanti" min = "1" max = "30" onchange="creazaTabelListe()"></p>
    <p id = "paraDescrieri"><label id = "paraLabel">Liste:</label></p>

    <input type = "hidden" name = "completat">
    <p><input id = "button-submit" type = "submit" value = "Conversie in phpBB"></p>
</form>
</body>
</html>