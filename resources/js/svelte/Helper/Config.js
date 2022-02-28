export let config = {
    env: "dev",
    prodUrl: "https://pu.techniknews.biz/",
    devUrl: "https://help-ukraine.ddev.site/"
};

export let groupedMarkersByCategory = {
    1: {name: "Unterkünfte", marker: []},
    2: {name: "Kleidungsspende", marker: []},
    3: {name: "Essen & Trinken", marker: []},
    4: {name: "Sonstiges", marker: []},
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
