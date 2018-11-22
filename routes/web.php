<?php
    
    /*
	|--------------------------------------------------------------------------
	| Web Routes
	|--------------------------------------------------------------------------
	|
	| Here is where you can register web routes for your application. These
	| routes are loaded by the RouteServiceProvider within a group which
	| contains the "web" middleware group. Now create something great!
	|
	*/
    
    use Illuminate\Support\Facades\Route;
    
    // STOCK-CHECKER FORM PAGE ROUTES:
    Route::get( '/', 'StockController@stockQuotes' )->name('rte.stock_checker.get');
    Route::post( '/', 'StockController@stockQuotes' )->name('rte.stock_checker.post');
    
    // HIGHEST PRICES ROUTES:
    Route::get( '/stocks/highest-market-prices', 'StockController@fetchHighestPrices' )->name('rte.stocks.highest_market_prices.get');
    
    // MARKET OVERVIEW ROUTES
    Route::get( '/stocks/market-overview', 'StockController@fetchMarketOverview' )->name('rte.stocks.market_overview.get');
    
    // STOCK-OVERVIEW ROUTES:
    Route::get( '/stocks/stock-overview', 'StockController@fetchStockOverview' )->name('rte.stocks.stock_overview.get');
    
