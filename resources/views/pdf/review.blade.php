<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>
        html {
            font-size: 0.70em;
        }

        table {
            border: none;
        }

        .grade {
            margin-left: 1em;
        }

        .grade1-5 {
            margin-left: 3em;
        }

        ol, ul {
            margin-top: 0;
        }
    </style>
</head>
<body>
Universitatea Babeș-Bolyai Cluj-Napoca<br>
Facultatea de Matematică și Informatică<br>
<b>Lucrări de licență/disertație în Informatică</b><br>
<br>
<p style="text-align: center; text-decoration: underline">
    Anexa 4: Referat de evaluare conducător ştiinţific
    <br>
    <b>Lucrare de licenţă</b>({{ now()->year }})
</p>
<p><b>Student</b> {{ $student->name }}</p>
<p><b>Specializarea:</b> Informatica Engleza</p>

<b>A.</b>Evaluare generală:
<table style="width: 100%">
    <tr>
        <td>({{ $student->review->overall == 4 ? 'X': ' ' }})Excelent</td>
        <td>({{ $student->review->overall == 3 ? 'X': ' ' }})Foarte bine</td>
        <td>({{ $student->review->overall == 2 ? 'X': ' ' }})Bine</td>
        <td>({{ $student->review->overall == 1 ? 'X': ' ' }})Satisfăcător</td>
    </tr>
</table>

<b>B.</b>Recomandare notă:
<div class="grade">({{ $student->review->grade_recommendation == 3 ? 'X': ' ' }}): 9-10</div>
<div class="grade">({{ $student->review->grade_recommendation == 2 ? 'X': ' ' }}): 7-8</div>
<div class="grade">({{ $student->review->grade_recommendation == 1 ? 'X': ' ' }}): 5-6</div>
<b>C.</b>Se vor avea, prin notarea pe o scală de la 1 la 5 (în care semnificaţia cifrelor este: 1 - nesatisfăcător, 2 - satisfăcător, 3 - bine, 4 - foarte bine şi 5 - excelent) următoarele aspecte:

<br><br>

<div><b>Studiul domeniului lucrării</b></div>
<ol style="list-style-type: lower-alpha">
    <li>Structură (concordanța cu titlul): @include('pdf.widget15', ['checked' => $student->review->structure])</li>
    <li>Aspecte originale (articol, publicaţie, comunicare, etc.): @include('pdf.widget15', ['checked' => $student->review->originality])</li>
    <li>Modul de sintetizare a rezultatelor din literatură: @include('pdf.widget15', ['checked' => $student->review->literature_results])</li>
    <li>Bibliografie relevantă și referințe în text: @include('pdf.widget15', ['checked' => $student->review->references])</li>
    <li>Formă, mod redactare, stil: @include('pdf.widget15', ['checked' => $student->review->form])</li>
</ol>

<br>

<div><b>Rezultate experimentale relevante</b></div>
<ol style="list-style-type: lower-alpha">
    <li>Analiza rezultatelor: @include('pdf.widget15', ['checked' => $student->review->result_analysis])</li>
    <li>Mod de prezentare rezultate (tabele, grafice, etc.): @include('pdf.widget15', ['checked' => $student->review->result_presentation])</li>
</ol>

<div><b>Aplicaţia practică</b></div>
<ol style="list-style-type: lower-alpha">
    <li>Complexitatea aplicației soft: @include('pdf.widget15', ['checked' => $student->review->app_complexity])</li>
    <li>
        Calitatea aplicației soft: @include('pdf.widget15', ['checked' => $student->review->app_quality])
        <ul style="list-style-type: disc;">
            <li>Ilustrarea (în lucrarea scrisă) a etapelor din ciclul de viaţă al aplicaţiei (analiză, proiectare, testare)</li>
            <li>Implementare</li>
            <li>După specificul aplicaţiei, se vor considera următoarele aspecte: validare, securitate, folosirea principiilor de dezvoltare, folosirea şabloanelor de proiectare, etc.</li>
        </ul>
    </li>
</ol>

<b>D.</b>Alte observaţii:
<pre>{{ $student->review->observations }}</pre>

<div style="position: fixed; bottom: 4em; left: 0">
    <b>Data:</b>
</div>
<div style="position: fixed; bottom: 4em; float: right; text-align: right">
    <b>Coordonator ştiinţific</b>
    <br>
    {{ $student->review->professor->name }}
    <br>
    <b>Semnătura</b>
</div>

</body>
</html>



