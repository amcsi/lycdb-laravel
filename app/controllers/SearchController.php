<?php

use Illuminate\Translation\Translator;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Http\Request;
use Lycee\Card\FetchService;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param FetchService $fetchService
     * @param ViewFactory $viewFactory
     * @param Request $request
     */
    public function __construct(FetchService $fetchService, ViewFactory $viewFactory, Request $request, TranslatorInterface $translator)
    {
        $this->fetchService = $fetchService;
        $this->viewFactory = $viewFactory;
        $this->request = $request;
        $this->translator = $translator;
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
        $results = $fetchService->getByRequest($requestVars, $paginator);

        $vars = [];
        $vars['cards'] = $results;

        $_ = $this->translator;

        $selectableCardTypes = [
            -2 => '-',
            -1 => $_->trans('non-character'),
            0 => $_->trans('Character'),
            1 => $_->trans('Area'),
            2 => $_->trans('Item'),
            3 => $_->trans('Event'),
        ];
        $vars['selectableCardTypes'] = $selectableCardTypes;

        $selectableCostTypes = [
            0 => '-',
            1 => $_->trans('payable by:'),
            2 => $_->trans('is exactly:'),
        ];
        $vars['selectableCostTypes'] = $selectableCostTypes;

        $selectableOperators = [
            1 => '≥',
            0 => '=',
            -1 => '≤',
        ];
        $vars['selectableOperators'] = $selectableOperators;

        $selectableElementTypes = [
            0 => '-',
            1 => $_->trans('has:'),
            2 => $_->trans('is:'),
        ];
        $vars['selectableElementTypes'] = $selectableElementTypes;
        $vars['paginator'] = $paginator;

        return $viewFactory->make('search', $vars);
    }
}
