module.exports = {
    configureWebpack: {
        devtool: "source-map"
    },

    chainWebpack: config => {
        config.plugin("html").tap(args => {
            //args[0].minify.removeAttributeQuotes = false;
            return args;
        });
    },

    devServer: {
        proxy:
            process.env.NODE_ENV === "production"
                ? "https://hfqserver.com/api/v1"
                : "http://hfq/api/v1"
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
