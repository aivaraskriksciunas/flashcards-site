const path = require( 'path' );

const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
    entry: './resources/js/app.js',

    mode: process.env.NODE_ENV,

    module: {
        rules: [
            {
                test: /\.s[ac]ss$/i,
                exclude: /node_modules/,
                use: [
                    MiniCssExtractPlugin.loader,
                    "css-loader", 
                    "sass-loader",
                ],
            },
        ],
    },

    plugins: [
        new MiniCssExtractPlugin({
            filename: "[name].css",
            chunkFilename: "[id].css",
        }),
    ],

    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, 'public'),
    }
}