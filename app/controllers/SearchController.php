<?php

use Illuminate\View\Factory as ViewFactory;
use Illuminate\Http\Request;
use Lycee\Card\FetchService;

class SearchController extends Controller {

    /**
     * @var FetchService
     */
    private $fetchService;
    /**
     * @var ViewFactory
     */
    private $viewFactory;
    /**
     * @var Request
     */
    private $request;

    /**
     * @param FetchService $fetchService
     * @param ViewFactory $viewFactory
     * @param Request $request
     */
    public function __construct(FetchService $fetchService, ViewFactory $viewFactory, Request $request)
    {
        $this->fetchService = $fetchService;
        $this->viewFactory = $viewFactory;
        $this->request = $request;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
	public function index()
	{
        $fetchService = $this->fetchService;
        $viewFactory = $this->viewFactory;
        $request = $this->request;

        $options = array ();
        $options['template'] = true;
        $options['pref_lang'] = 'en'; // prefer english

        $requestVars = $request->all();
        $results = $fetchService->getByRequest($requestVars);

        $vars = [];
        $vars['cards'] = $results;

        return $viewFactory->make('search', $vars);
    }
}
