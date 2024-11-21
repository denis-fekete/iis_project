<style>
    .title {
        font-weight: bold;
        font-size: 1.3em;
        color: #3E3C3E;
    }

    .title_block {
        height: 5vh;
        background-color: #E1DDA8;
        padding: 1em;
        border: solid 2px #3E3C3E;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 0.5em;
    }

    .grid_vertical {
        display: grid;
        grid-template-columns: repeat(auto-fit, 1fr);
        gap: 1em;
        margin-top: 1em;
        margin-bottom: 1em;
    }

    .grid_vertical_2_collumns {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-inline: 5%;
        margin-top: 1em;
        margin-bottom: 1em;
    }

    .grid_horizontal {
        display: flex;
        gap: 1em;
        margin-top: 0.5em;
    }


    .card {
        background-color: #E1DDA8;
        margin-inline: 5%;
        padding: 1em;
        padding-bottom: 1em;
        border-radius: 20px;
        box-shadow: 2px 2px 5px #3E3C3E;
    }

    button,input[type="submit"] {
        font-size: 1em;

        height: auto;
        width: auto;

        padding-inline: 0.4em;
        padding-top: 0.1em;
        padding-bottom: 0.1em;

        background: #F7BE58;
        color: #3E3C3E;

        border: none;
        border-radius: 20px;
        box-shadow: 2px 2px 5px #3E3C3E;
    }

    button:hover, input[type="submit"]:hover {
        background: #E1DDA8;
    }

    input {
        background: #EEEBCC;
        color: #3E3C3E;
        margin-bottom: 0.5em;
    }

    input:disabled {
        background: #9DD9D2;
        color: #3E3C3E;
        font-style: italic;
    }

    .text_link {
        text-decoration: underline;
        color: #885E97;
    }

    .text_theme {
        color: #878381;
    }

    .delete_btn {
        background-color: #FD5620;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 1em;
        text-align: left;
        background-color: #f9f9f9;
        text-align: center;
    }

    .table th {
        background-color: #FAAC40;;
        color: #333;
        text-transform: uppercase;
        padding: 10px;
        border: 1px solid #ddd;
    }

    .table tr:hover {
        background-color: #ffeeba;
    }

    .table td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    .schedule-save {
        text-align: center;
    }

    .lecture-title {
        font-size: 2em;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
    }

    .lecture-poster {
        display: block;
        margin: 0 auto 20px auto;
        border-radius: 10px;
        max-width: 100%;
        max-height: 500px;
    }

    .lecture-description {
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .lecture-attributes {
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .lecture-control {
        text-align: center;
        margin-top: 20px;
    }

    .conference-list-image {
        display: block;
        margin: 0 auto 20px auto;
        border-radius: 10px;
        max-width: 100%;
        max-height: 400px;
    }

</style>
