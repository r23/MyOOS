const path = require('path');
const TerserPlugin = require("terser-webpack-plugin");

module.exports = {
    mode: 'production', // 'development',
    entry: path.resolve(__dirname, './src/index.js'),
    output: {
        filename: 'static/js/[name].js', // Use a placeholder for filename
        path: path.resolve(__dirname, 'dist'),
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                include: [path.resolve(__dirname, 'src'), require.resolve('@c-frame/aframe-physics-system') ],
                use: {
                  loader: 'babel-loader', // or whatever loader you're using to parse modules
                  options: {}
                }
              }

        ]
    },
    optimization: {
        splitChunks: {
          chunks: 'all', // Split all chunks
          minSize: 20000, // Minimum size of chunk to be generated
          maxSize: 0, // No maximum size limit
          minChunks: 1, // Minimum number of chunks that must share a module
          maxAsyncRequests: 30, // Maximum number of parallel requests
          maxInitialRequests: 30, // Maximum number of initial requests
          automaticNameDelimiter: '~', // Delimiter for generated names
          enforceSizeThreshold: 50000, // Threshold for enforcing chunk size
          cacheGroups: {
            defaultVendors: {
              test: /[\\/]node_modules[\\/]/,
              priority: -10,
              reuseExistingChunk: true,
            },
            default: {
              minChunks: 2,
              priority: -20,
              reuseExistingChunk: true,
            },
          },
        },
        runtimeChunk: {name: (entrypoint) => `runtime-${entrypoint.name}`}, // Use a separate chunk for runtime code
   },
   plugins: [
    new TerserPlugin({
      terserOptions: {
        // Use the default options for terser
      },
    }),
  ],
};
