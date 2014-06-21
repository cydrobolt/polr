function select_text()
{
    var urlbox = document.getElementById("i");
    if(urlbox)
    {
        urlbox.focus();
        urlbox.select();
    }
}

window.onload = select_text;

