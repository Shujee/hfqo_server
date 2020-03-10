module.exports = {
    configureWebpack: {
        devtool: "source-map"
    },

    devServer: {
        proxy:
            process.env.NODE_ENV === "production"
                ? "https://hfqserver.com/api/v1"
                : "http://hfq:8080/api/v1"
    },

    outputDir: "../public",

    // modify the location of the generated HTML file.
    // make sure to do this only in production.
    indexPath:
        process.env.NODE_ENV === "production"
            ? "../resources/views/welcome.blade.php"
            : "index.html",

    pluginOptions: {
        moment: {
            locales: ["utc"]
        }
    }
};
