// Contient une instance de XMLHttpRequest
var xmlHttp = createXmlHttpRequestObject();
// Afficher les messages d'erreur (true) ou dégrader vers un comportement non-AJAX (false)
var showErrors = true;
// This is true when the Place Order button is clicked, false otherwise
var placingOrder = false;
// Contient le lien ou le formulaire cliqué ou soumis par le visiteur
var actionObject = '';

// Crée une instance XMLHttpRequest
function createXmlHttpRequestObject()
{
// Stockera l'objet XMLHttpRequest
    var xmlHttp;
// Créer l'objet XMLHttpRequest
    try
    {
// Tenter de créer l'objet XMLHttpRequest natif
        xmlHttp = new XMLHttpRequest();
    }
    catch(e)
    {
// Supposer IE6 ou antérieur
        var XmlHttpVersions = new Array(
            "MSXML2.XMLHTTP.6.0", "MSXML2.XMLHTTP.5.0", "MSXML2.XMLHTTP.4.0",
            "MSXML2.XMLHTTP.3.0", "MSXML2.XMLHTTP", "Microsoft.XMLHTTP");
// Essayer chaque ID jusqu'à ce qu'un fonctionne
        for (i = 0; i < XmlHttpVersions.length && !xmlHttp; i++)
        {
            try
            {
// Tenter de créer l'objet XMLHttpRequest
                xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
            }
            catch (e) {} // Ignorer l'erreur potentielle
        }
    }
// Si l'objet XMLHttpRequest a été créé avec succès, le retourner
    if (xmlHttp)
    {
        return xmlHttp;
    }
// Si une erreur s'est produite, la transmettre à handleError
    else
    {
        handleError("Erreur lors de la création de l'objet XMLHttpRequest.");
    }
}

// Affiche le message d'erreur ou dégrade vers un comportement non-AJAX
function handleError($message)
{
// Ignorer les erreurs si showErrors est false
    if (showErrors)
    {
// Afficher le message d'erreur
        alert("Erreur rencontrée : \n" + $message);
        return false;
    }
// Revenir au comportement non-AJAX
    else if (!actionObject.tagName)
    {
        return true;
    }
// Revenir au comportement non-AJAX en suivant le lien
    else if (actionObject.tagName == 'A')
    {
        window.location = actionObject.href;
    }
// Revenir au comportement non-AJAX en soumettant le formulaire
    else if (actionObject.tagName == 'FORM')
    {
        actionObject.submit();
    }
}

// Ajoute un produit au panier d'achat
function addProductToCart(form)
{
// Afficher le message "Mise à jour"
    document.getElementById('updating').style.visibility = 'visible';
// Dégrader vers la soumission de formulaire classique si XMLHttpRequest n'est pas disponible
    if (!xmlHttp) return true;

// Créer l'URL que nous ouvrons de manière asynchrone
    request = form.action + '&AjaxRequest';
    params = '';
// obtenir les attributs sélectionnés
    formSelects = form.getElementsByTagName('SELECT');
    if (formSelects)
    {
        for (i = 0; i < formSelects.length; i++)
        {
            params += '&' + formSelects[i].name + '=';
            selected_index = formSelects[i].selectedIndex;
            params += encodeURIComponent(formSelects[i][selected_index].text);
        }
    }

// Tenter de se connecter au serveur
    try
    {
// Continuer seulement si l'objet XMLHttpRequest n'est pas occupé
        if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
        {
// Faire une requête serveur pour valider les données extraites
            xmlHttp.open("POST", request, true);
            xmlHttp.setRequestHeader("Content-Type",
                "application/x-www-form-urlencoded");
            xmlHttp.onreadystatechange = addToCartStateChange;
            xmlHttp.send(params);
        }
    }
    catch (e)
    {
// Gérer l'erreur
        handleError(e.toString());
    }

// Arrêter la soumission de formulaire classique si l'action AJAX a réussi
    return false;
}

// Fonction qui récupère la réponse HTTP
function addToCartStateChange()
{
// Lorsque readyState est 4, nous lisons également la réponse du serveur
    if (xmlHttp.readyState == 4)
    {
// Continuer seulement si le statut HTTP est "OK"
        if (xmlHttp.status == 200)
        {
            try
            {
                updateCartSummary();
            }
            catch (e)
            {
                handleError(e.toString());
            }
        }
        else
        {
            handleError(xmlHttp.statusText);
        }
    }
}

// Traiter la réponse du serveur
function updateCartSummary()
{
// Lire la réponse
    response = xmlHttp.responseText;
// Erreur serveur ?
    if (response.indexOf("ERRNO") >= 0 || response.indexOf("error") >= 0)
    {
        handleError(response);
    }
    else
    {
// Extraire le contenu de l'élément div cart_summary
        var cartSummaryRegEx = /^<div class="box" id="cart-summary">([\s\S]*)<\/div>$/m;
        matches = cartSummaryRegEx.exec(response);
        response = matches[1];
// Mettre à jour la boîte récapitulative du panier et masquer le message de chargement
        document.getElementById("cart-summary").innerHTML = response;
// Masquer le message "Mise à jour..."
        document.getElementById('updating').style.visibility = 'hidden';
    }
}
// Appelée lors des actions de mise à jour du panier d'achat
function executeCartAction(obj)
{
    // Degrade to classical form submit for Place Order action
    if (placingOrder) return true;
// Afficher le message "Mise à jour..."
    document.getElementById('updating').style.visibility = 'visible';
// Dégrader vers la soumission de formulaire classique si XMLHttpRequest n'est pas disponible
    if (!xmlHttp) return true;

// Sauvegarder la référence de l'objet
    actionObject = obj;

// Initialiser la réponse et les paramètres
    response = '';
    params = '';

// Si un lien a été cliqué, nous obtenons son attribut href
    if (obj.tagName == 'A')
    {
        url = obj.href + '&AjaxRequest';
    }
// Si le formulaire a été soumis, nous obtenons ses éléments
    else
    {
        url = obj.action + '&AjaxRequest';
        formElements = obj.getElementsByTagName('INPUT');
        if (formElements)
        {
            for (i = 0; i < formElements.length; i++)
            {
                if (formElements[i].name != 'place_order')
                {
                    params += '&' + formElements[i].name + '=';
                    params += encodeURIComponent(formElements[i].value);
                }
            }
        }
    }

// Tenter de se connecter au serveur
    try
    {
// Faire une requête serveur seulement si l'objet XMLHttpRequest n'est pas occupé
        if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
        {
            xmlHttp.open("POST", url, true);
            xmlHttp.setRequestHeader("Content-Type",
                "application/x-www-form-urlencoded");
            xmlHttp.onreadystatechange = cartActionStateChange;
            xmlHttp.send(params);
        }
    }
    catch (e)
    {
// Gérer l'erreur
        handleError(e.toString());
    }

// Arrêter la soumission de formulaire classique si l'action AJAX a réussi
    return false;
}

// Fonction qui récupère la réponse HTTP
function cartActionStateChange()
{
// Lorsque readyState est 4, nous lisons également la réponse du serveur
    if (xmlHttp.readyState == 4)
    {
// Continuer seulement si le statut HTTP est "OK"
        if (xmlHttp.status == 200)
        {
            try
            {
// Lire la réponse
                response = xmlHttp.responseText;
// Erreur serveur ?
                if (response.indexOf("ERRNO") >= 0 || response.indexOf("error") >= 0)
                {
                    handleError(response);
                }
                else
                {
// Mettre à jour le panier
                    document.getElementById("contents").innerHTML = response;
// Masquer le message "Mise à jour..."
                    document.getElementById('updating').style.visibility = 'hidden';
                }
            }
            catch (e)
            {
// Gérer l'erreur
                handleError(e.toString());
            }
        }
        else
        {
// Gérer l'erreur
            handleError(xmlHttp.statusText);
        }
    }
}