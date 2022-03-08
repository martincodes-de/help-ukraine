export let config = {
    env: "prod",
    prodUrl: "https://pu.techniknews.biz/",
    devUrl: "https://help-ukraine.ddev.site/"
};

export let groupedMarkersByCategory = {
    1: {name: "Accommodation", marker: []},
    2: {name: "Clothing donations", marker: []},
    3: {name: "Food and drinks", marker: []},
    4: {name: "Other", marker: []},
};

export function routeTo(pathWithoutBeginningSlash) {
    const env = config.env;
    let baseUrl = config.prodUrl;

    if (env === "dev") {
        baseUrl = config.devUrl;
    }

    if (pathWithoutBeginningSlash.charAt(0) === "/") {
        pathWithoutBeginningSlash = pathWithoutBeginningSlash.substring(1);
    }

    return baseUrl+pathWithoutBeginningSlash;
}
