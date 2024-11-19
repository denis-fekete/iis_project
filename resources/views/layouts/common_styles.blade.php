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
    }
</style>
