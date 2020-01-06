module.exports = {
  configureWebpack: {
    devtool: 'source-map'
  },

  devServer: {
      proxy: process.env.MIX_API_ENDPOINT,
  },

  outputDir: '../public',
  
  // modify the location of the generated HTML file.
  // make sure to do this only in production.
  indexPath: process.env.NODE_ENV === 'production'
  ? '../resources/views/welcome.blade.php'
  : 'index.html',

  pluginOptions: {
    moment: {
      locales: [
        'utc'
      ]
    }
  }
}
