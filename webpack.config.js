const path = require('path');
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const fs = require('fs')

module.exports = {
  entry: [
    './resources/assets/js/index.js',
    './resources/assets/scss/style.scss'
  ],
  output: {
    filename: 'js/app.min.js',
	path: path.resolve(__dirname, 'web')
  },
  devtool: "source-map",
  module: {
    rules: [{
        test: /\.js$/,
        include: path.resolve(__dirname, 'resources/assets/js'),
        use: {
          loader: 'babel-loader',
          options: {
            presets: 'env'
          }
        }
      },
      {
        test: /\.(css|sass|scss)$/,
        include: path.resolve(__dirname, 'resources/assets/scss'),
        use: ExtractTextPlugin.extract({
          use: [{
              loader: "css-loader",
              options: {
                sourceMap: true,
                minimize: true,
                url: false
              }
            }, 
			{
			  loader: 'postcss-loader', // Run post css actions
			  options: {
				plugins: function () { // post css plugins, can be exported to postcss.config.js
				  return [
					require('precss'),
					require('autoprefixer')
				  ];
				}
			  }
			},
            {
              loader: "sass-loader",
              options: {
                sourceMap: true
              }
            }
          ]
        })
      },
    ]
  },
  plugins: [
    new ExtractTextPlugin({
      filename: 'css/app.min.css',
      allChunks: true,
    }),
  ]
};