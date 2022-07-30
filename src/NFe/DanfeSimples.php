<?php
/*
 * Author Newton Pasqualini Filho (newtonpasqualini at gmail dot com)
 */

namespace NFePHP\DA\NFe;

use NFePHP\DA\Legacy\Dom;
use NFePHP\DA\Legacy\Pdf;
use NFePHP\DA\Common\DaCommon;

class DanfeSimples extends DaCommon
{

    /**
     * Tamanho do Papel
     *
     * @var string
     */
    public $papel = 'A5';
    /**
     * XML NFe
     *
     * @var string
     */
    protected $xml;
    /**
     * mesagens de erro
     *
     * @var string
     */
    protected $errMsg = '';
    /**
     * status de erro true um erro ocorreu false sem erros
     *
     * @var boolean
     */
    protected $errStatus = false;
    /**
     * Dom Document
     *
     * @var \NFePHP\DA\Legacy\Dom
     */
    protected $dom;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $infNFe;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $ide;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $entrega;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $retirada;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $emit;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $dest;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $enderEmit;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $enderDest;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $det;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $cobr;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $dup;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $ICMSTot;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $ISSQNtot;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $transp;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $transporta;
    /**
     * Node
     *
     * @var \DOMNode
     */
    protected $veicTransp;
    /**
     * Node reboque
     *
     * @var \DOMNode
     */
    protected $reboque;
    /**
     * Node infAdic
     *
     * @var \DOMNode
     */
    protected $infAdic;
    /**
     * Tipo de emissão
     *
     * @var integer
     */
    protected $tpEmis;
    /**
     * Node infProt
     *
     * @var \DOMNode
     */
    protected $infProt;
    /**
     * 1-Retrato/ 2-Paisagem
     *
     * @var integer
     */
    protected $tpImp;
    /**
     * Node compra
     *
     * @var \DOMNode
     */
    protected $compra;

    /*
     * Guarda a estrutura da NF como Array para
     * interagir de maneira nativa com os dados
     * do XML da NFe
     */
    protected $nfeArray = [];

    /**
     * __construct
     *
     * @name  __construct
     *
     * @param string $xml Conteúdo XML da NF-e (com ou sem a tag nfeProc)
     */
    public function __construct($xml, $orientacao = 'P')
    {
        $this->loadDoc($xml);
        $this->orientacao = $orientacao;
    }

    private function loadDoc($xml)
    {
        $this->xml = $xml;
        if (!empty($xml)) {
            $this->dom = new Dom();
            $this->dom->loadXML($this->xml);
            if (empty($this->dom->getElementsByTagName("infNFe")->item(0))) {
                throw new \Exception('Isso não é um NFe.');
            }
            $this->nfeProc = $this->dom->getElementsByTagName("nfeProc")->item(0);
            $this->infNFe  = $this->dom->getElementsByTagName("infNFe")->item(0);
            $this->ide     = $this->dom->getElementsByTagName("ide")->item(0);
            if ($this->getTagValue($this->ide, "mod") != '55') {
                throw new \Exception("O xml deve ser NF-e modelo 55.");
            }
            $this->entrega    = $this->dom->getElementsByTagName("entrega")->item(0);
            $this->retirada   = $this->dom->getElementsByTagName("retirada")->item(0);
            $this->emit       = $this->dom->getElementsByTagName("emit")->item(0);
            $this->dest       = $this->dom->getElementsByTagName("dest")->item(0);
            $this->enderEmit  = $this->dom->getElementsByTagName("enderEmit")->item(0);
            $this->enderDest  = $this->dom->getElementsByTagName("enderDest")->item(0);
            $this->det        = $this->dom->getElementsByTagName("det");
            $this->cobr       = $this->dom->getElementsByTagName("cobr")->item(0);
            $this->dup        = $this->dom->getElementsByTagName('dup');
            $this->ICMSTot    = $this->dom->getElementsByTagName("ICMSTot")->item(0);
            $this->ISSQNtot   = $this->dom->getElementsByTagName("ISSQNtot")->item(0);
            $this->transp     = $this->dom->getElementsByTagName("transp")->item(0);
            $this->transporta = $this->dom->getElementsByTagName("transporta")->item(0);
            $this->veicTransp = $this->dom->getElementsByTagName("veicTransp")->item(0);
            $this->detPag     = $this->dom->getElementsByTagName("detPag");
            $this->reboque    = $this->dom->getElementsByTagName("reboque")->item(0);
            $this->infAdic    = $this->dom->getElementsByTagName("infAdic")->item(0);
            $this->compra     = $this->dom->getElementsByTagName("compra")->item(0);
            $this->tpEmis     = $this->getTagValue($this->ide, "tpEmis");
            $this->tpImp      = $this->getTagValue($this->ide, "tpImp");
            $this->infProt    = $this->dom->getElementsByTagName("infProt")->item(0);
        }
    }

    protected function monta($logo = null)
    {
        $this->pdf = '';
        //se a orientação estiver em branco utilizar o padrão estabelecido na NF
        if (empty($this->orientacao)) {
            $this->orientacao = 'L';
        }
        $this->pdf = new Pdf($this->orientacao, 'mm', $this->papel);
        if ($this->orientacao == 'L') {
            if ($this->papel == 'A5') {
                $this->maxW = 210;
                $this->maxH = 148;
            } elseif (is_array($this->papel)) {
                $this->maxW = $this->papel[0];
                $this->maxH = $this->papel[1];
            }
        } else {
            if ($this->papel == 'A5') {
                $this->maxW = 148;
                $this->maxH = 210;
            } elseif (is_array($this->papel)) {
                $this->maxW = $this->papel[0];
                $this->maxH = $this->papel[1];
            }
        }
        //Caso a largura da etiqueta seja pequena <=110mm,
        //Definimos como pequeno, para diminuir as fontes e tamanhos das células
        if ($this->maxW <= 130) {
            $pequeno = true;
        } else {
            $pequeno = false;
        }
        // estabelece contagem de paginas
        $this->pdf->aliasNbPages();
        // fixa as margens
        $this->pdf->setMargins($this->margesq, $this->margsup);
        $this->pdf->setDrawColor(0, 0, 0);
        $this->pdf->setFillColor(255, 255, 255);
        // inicia o documento
        $this->pdf->open();
        // adiciona a primeira página
        $this->pdf->addPage($this->orientacao, $this->papel);
        $this->pdf->setLineWidth(0.1);
        $this->pdf->settextcolor(0, 0, 0);
        //Configura o pagebreak para não quebrar com 2cm do bottom.
        $this->pdf->setAutoPageBreak(true, $this->margsup);

        $volumes = [];
        $pesoL = 0.000;
        $pesoB = 0.000;
        $totalVolumes = 0;

        // Normalizar o array de volumes quando tem apenas 1 volumes
        // if (!isset($this->nfeArray['NFe']['infNFe']['transp']['vol'][0])) {
        //     $this->nfeArray['NFe']['infNFe']['transp']['vol'] = [
        //         $this->nfeArray['NFe']['infNFe']['transp']['vol']
        //     ];
        // }

        foreach ($this->transp->getElementsByTagName('vol') as $vol) {
            $espVolume = !empty($this->transp->getElementsByTagName("esp")->item(0)->nodeValue) ?
                $this->transp->getElementsByTagName("esp")->item(0)->nodeValue : 'VOLUME';
            
            //Caso não esteja especificado no xml, irá ser mostrado no danfe a palavra VOLUME

            if (!isset($volumes[$espVolume])) {
                $volumes[$espVolume] = 0;
            }

            // Caso a quantidade de volumes não esteja presente no XML, soma-se zero
            $volumes[$espVolume] += !empty($vol->getElementsByTagName("qVol")->item(0)->nodeValue) ?
                $vol->getElementsByTagName("qVol")->item(0)->nodeValue : 0;
            // Caso a quantidade de volumes não esteja presente no XML, soma-se zero
            $totalVolumes += !empty($vol->getElementsByTagName("qVol")->item(0)->nodeValue) ?
                $vol->getElementsByTagName("qVol")->item(0)->nodeValue : 0;
            // Caso o peso bruto não esteja presente no XML, soma-se zero
            $pesoB += !empty($vol->getElementsByTagName("pesoB")->item(0)->nodeValue) ?
                $vol->getElementsByTagName("pesoB")->item(0)->nodeValue : 0;
            // Caso o peso liquido não esteja presente no XML, soma-se zero
            $pesoL += !empty($vol->getElementsByTagName("pesoL")->item(0)->nodeValue) ?
                $vol->getElementsByTagName("pesoL")->item(0)->nodeValue : 0;
        }
        // LINHA 1
        $this->pdf->setFont('Arial', 'B', $pequeno ? 10 : 12);
        $this->pdf->cell(
            ($this->maxW - ($this->margesq * 2)),
            $pequeno ? 5 : 6,
            "DANFE SIMPLIFICADO - ETIQUETA",
            1,
            1,
            'C',
            1
        );

        // LINHA 2
        $dEmi  = !empty($this->ide->getElementsByTagName("dEmi")->item(0)->nodeValue) ?
            $this->ide->getElementsByTagName("dEmi")->item(0)->nodeValue : '';
        if ($dEmi == '') {
            $dEmi  = !empty($this->ide->getElementsByTagName("dhEmi")->item(0)->nodeValue) ?
                $this->ide->getElementsByTagName("dhEmi")->item(0)->nodeValue : '';
            $aDemi = explode('T', $dEmi);
            $dEmi  = $aDemi[0];
        }
        $dataEmissao = $this->ymdTodmy($dEmi);
        $c1 = ($this->maxW - ($this->margesq * 2)) / 4;
        $this->pdf->setFont('Arial', 'B', $pequeno ? 8 : 10);
        $this->pdf->cell($c1, $pequeno ? 4 : 5, "TIPO NF", 1, 0, 'C', 1);
        $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
        $this->pdf->cell(
            $c1,
            5,
            "{$this->ide->getElementsByTagName('tpNF')->item(0)->nodeValue} - " .
                ($this->ide->getElementsByTagName('tpNF')->item(0)->nodeValue == 1 ? 'Saida' : 'Entrada'),
            1,
            0,
            'C',
            1
        );
        $this->pdf->setFont('Arial', 'B', $pequeno ? 6 : 10);
        $this->pdf->cell($c1, $pequeno ? 4 : 5, "DATA EMISSAO", 1, 0, 'C', 1);
        $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
        $this->pdf->cell($c1, $pequeno ? 4 : 5, "{$dataEmissao}", 1, 1, 'C', 1);

        // LINHA 3
        $this->pdf->setFont('Arial', 'B', $pequeno ? 8 : 10);
        $this->pdf->cell($c1, $pequeno ? 4 : 5, "NUMERO", 1, 0, 'C', 1);
        $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
        $this->pdf->cell($c1, $pequeno ? 4 : 5, "{$this->ide->getElementsByTagName('nNF')->item(0)->nodeValue}", 1, 0, 'C', 1);
        $this->pdf->setFont('Arial', 'B', $pequeno ? 8 : 10);
        $this->pdf->cell($c1, $pequeno ? 4 : 5, "SERIE", 1, 0, 'C', 1);
        $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
        $this->pdf->cell($c1, $pequeno ? 4 : 5, "{$this->ide->getElementsByTagName('serie')->item(0)->nodeValue}", 1, 1, 'C', 1);

        // LINHA 4
        $chave = substr($this->infNFe->getAttribute("Id"), 3);
        // $this->pdf->setFont('Arial', 'B', $pequeno ? 7 : 10);
        // $this->pdf->cell($c1, $pequeno ? 4 : 5, "CHAVE DE ACESSO", 1, 0, 'C', 1);
        // $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
        // $this->pdf->cell(($c1 * 3), $pequeno ? 4 : 5, "{$chave}", 1, 1, 'C', 1);

        // LINHA 5
        $this->pdf->setFont('Arial', 'B', $pequeno ? 8 : 10);
        $this->pdf->cell($c1, $pequeno ? 4 : 5, "PROTOCOLO", 1, 0, 'C', 1);
        $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
        if (isset($this->nfeProc)) {
            $texto  = !empty($this->nfeProc->getElementsByTagName("nProt")->item(0)->nodeValue)
                ? $this->nfeProc->getElementsByTagName("nProt")->item(0)->nodeValue
                : '';
            $dtHora = $this->toDateTime(
                $this->nfeProc->getElementsByTagName("dhRecbto")->item(0)->nodeValue
            );
            if ($texto != '' && $dtHora) {
                $texto .= "  -  " . $dtHora->format('d/m/Y H:i:s');
            }
        } else {
            $texto = '';
        }
        $this->pdf->cell(
            ($c1 * 3),
            $pequeno ? 4 : 5,
            "{$texto}",
            1,
            1,
            'C',
            1
        );

        $this->pdf->ln();
        $this->pdf->setFont('Arial', 'B', $pequeno ? 8 : 10);
        $this->pdf->cell(($c1 * 4), $pequeno ? 5 : 6, "CHAVE DE ACESSO", 0, 1, 'C', 1);

        $y = $this->pdf->getY();
        $this->pdf->setFillColor(0, 0, 0);
        if ($pequeno) {
            //caso seja etiqueta pequena, aumenta o code128 para
            //que uma impressora de 203dpi consiga imprimir um código legível
            $this->pdf->code128($this->margesq * 2, $y, $chave, ($this->maxW - $this->margesq * 4), 15);
        } else {
            $this->pdf->code128(($c1 / 2), $y, $chave, ($c1 * 3), 15);
        }
        $this->pdf->setFillColor(255, 255, 255);
        $this->pdf->ln();
        $this->pdf->ln();
        $texto = $this->formatField(
            $chave,
            "#### #### #### #### #### #### #### #### ####"
        );
        $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
        $this->pdf->cell(($c1 * 4), $pequeno ? 5 : 6, $texto, 0, 1, 'C', 1);
        $this->pdf->ln();
        // LINHA 6
        $this->pdf->setFont('Arial', 'B', $pequeno ? 10 : 12);
        $this->pdf->cell(($c1 * 4), $pequeno ? 5 : 6, "EMITENTE", 1, 1, 'C', 1);

        // LINHA 7
        $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
        $this->pdf->multiCell(
            ($c1 * 4),
            $pequeno ? 4 : 5,
            "{$this->emit->getElementsByTagName("xNome")->item(0)->nodeValue}",
            1,
            'C',
            false
        );

        // LINHA 8
        if (!empty($this->emit->getElementsByTagName("CNPJ")->item(0)->nodeValue)) {
            $texto = $this->formatField(
                $this->emit->getElementsByTagName("CNPJ")->item(0)->nodeValue,
                "###.###.###/####-##"
            );
        } else {
            $texto = !empty($this->emit->getElementsByTagName("CPF")->item(0)->nodeValue)
                ? $this->formatField(
                    $this->emit->getElementsByTagName("CPF")->item(0)->nodeValue,
                    "###.###.###-##"
                )
                : '';
        }
        $this->pdf->cell(($c1 * 2.2), $pequeno ? 4 : 5, "CNPJ/CPF: {$texto}", 1, 0, 'C', 1);
        $IE = $this->emit->getElementsByTagName("IE");
        $texto = ($IE && $IE->length > 0) ? $IE->item(0)->nodeValue : '';
        $this->pdf->cell(
            ($c1 * 1.8),
            $pequeno ? 4 : 5,
            @"IE: {$texto}",
            1,
            1,
            'C',
            1
        );
        $cep = $this->formatField(
            $this->getTagValue($this->enderEmit, "CEP"),
            "##.###-###"
        );
        $enderecoEmit  = "{$this->getTagValue($this->enderEmit, "xMun")}"
            . " / {$this->getTagValue($this->enderEmit, "UF")}"
            . " - CEP {$cep}";

        // LINHA 9
        $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
        $this->pdf->cell(($c1 * 4), $pequeno ? 4 : 5, "{$enderecoEmit}", 1, 1, 'C', 1);

        // LINHA 10
        $this->pdf->setFont('Arial', 'B', $pequeno ? 10 : 12);
        $this->pdf->cell(($c1 * 4), $pequeno ? 5 : 6, "DESTINATARIO", 1, 1, 'C', 1);

        // LINHA 11
        $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
        $this->pdf->multiCell(
            ($c1 * 4),
            $pequeno ? 4 : 5,
            "{$this->dest->getElementsByTagName("xNome")->item(0)->nodeValue}",
            1,
            'C',
            false
        );

        // LINHA 12
        if (!empty($this->dest->getElementsByTagName("CNPJ")->item(0)->nodeValue)) {
            $texto = $this->formatField(
                $this->dest->getElementsByTagName("CNPJ")->item(0)->nodeValue,
                "###.###.###/####-##"
            );
        } else {
            $texto = !empty($this->dest->getElementsByTagName("CPF")->item(0)->nodeValue)
                ? $this->formatField(
                    $this->dest->getElementsByTagName("CPF")->item(0)->nodeValue,
                    "###.###.###-##"
                )
                : '';
        }
        $this->pdf->cell(($c1 * 2.2), $pequeno ? 4 : 5, "CNPJ/CPF: {$texto}", 1, 0, 'C', 1);

        $IE    = $this->dest->getElementsByTagName("IE");
        $texto = ($IE && $IE->length > 0) ? $IE->item(0)->nodeValue : '';
        $this->pdf->cell(
            ($c1 * 1.8),
            $pequeno ? 4 : 5,
            @"IE: {$texto}",
            1,
            1,
            'C',
            1
        );
        if ($this->entrega) {
            $cep = !empty($this->entrega->getElementsByTagName("CEP")->item(0)->nodeValue) ?
                $this->entrega->getElementsByTagName("CEP")->item(0)->nodeValue : '';
            $cep = $this->formatField($cep, "##.###-###");
            $enderecoLinha1 = "{$this->entrega->getElementsByTagName("xLgr")->item(0)->nodeValue}";
            if (!empty($this->entrega->getElementsByTagName("nro")->item(0)->nodeValue)) {
                $enderecoLinha1 .= ", {$this->entrega->getElementsByTagName("nro")->item(0)->nodeValue}";
            }
            $enderecoLinha2 = '';
            if (!empty($this->entrega->getElementsByTagName("xCpl")->item(0)->nodeValue)) {
                $enderecoLinha2 .= "{$this->entrega->getElementsByTagName("xCpl")->item(0)->nodeValue} - ";
            }
            $enderecoLinha2 .= "{$this->entrega->getElementsByTagName("xMun")->item(0)->nodeValue}"
                . " / {$this->entrega->getElementsByTagName("UF")->item(0)->nodeValue}"
                . " - CEP {$cep}";
        } else {
            $cep = !empty($this->dest->getElementsByTagName("CEP")->item(0)->nodeValue)
                ? $this->dest->getElementsByTagName("CEP")->item(0)->nodeValue
                : '';
            $cep = $this->formatField($cep, "##.###-###");
            $enderecoLinha1 = "{$this->dest->getElementsByTagName("xLgr")->item(0)->nodeValue}";
            if (!empty($this->dest->getElementsByTagName("nro")->item(0)->nodeValue)) {
                $enderecoLinha1 .= ", {$this->dest->getElementsByTagName("nro")->item(0)->nodeValue}";
            }
            $enderecoLinha2 = '';
            if (!empty($this->dest->getElementsByTagName("xCpl")->item(0)->nodeValue)) {
                $enderecoLinha2 .= "{$this->dest->getElementsByTagName("xCpl")->item(0)->nodeValue} - ";
            }
            $enderecoLinha2 .= "{$this->dest->getElementsByTagName("xMun")->item(0)->nodeValue}"
                . " / {$this->dest->getElementsByTagName("UF")->item(0)->nodeValue}"
                . " - CEP {$cep}";
        }

        $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
        $this->pdf->cell(($c1 * 4), $pequeno ? 4 : 5, "{$enderecoLinha1}", 1, 1, 'C', 1);

        $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
        $this->pdf->cell(($c1 * 4), $pequeno ? 4 : 5, "{$enderecoLinha2}", 1, 1, 'C', 1);
        
        if (
            $this->transp->getElementsByTagName("modFrete")->item(0)->nodeValue != 9
            && $this->transporta
        ) {
            $this->pdf->setFont('Arial', 'B', $pequeno ? 10 : 12);
            $this->pdf->cell(($c1 * 4), $pequeno ? 5 : 6, "TRANSPORTADORA", 1, 1, 'C', 1);
            $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
            $this->pdf->cell(
                ($c1 * 4),
                $pequeno ? 5 : 6,
                "{$this->transporta->getElementsByTagName("xNome")->item(0)->nodeValue}",
                1,
                1,
                'C',
                1
            );
        }
        
        if ($totalVolumes > 0) {
            foreach ($volumes as $esp => $qVol) {
                $this->pdf->cell(
                    ($c1 * 4),
                    $pequeno ? 5 : 6,
                    "{$esp} x {$qVol}",
                    1,
                    1,
                    'C',
                    1
                );
            }
        }

        $pesoL = number_format($pesoL, 3, ',', '.');
        $pesoB = number_format($pesoB, 3, ',', '.');
        if ($pesoL > 0 || $pesoB > 0) {
            $this->pdf->cell(
                ($c1 * 4),
                $pequeno ? 5 : 6,
                "PESO LIQ {$pesoL} / PESO BRT {$pesoB}",
                1,
                1,
                'C',
                1
            );
        }
        $this->pdf->setFont('Arial', 'B', $pequeno ? 10 : 12);
        $this->pdf->cell(($c1 * 2), $pequeno ? 5 : 6, "TOTAL DA NF-e", 1, 0, 'C', 1);
        $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
        $vNF = number_format($this->ICMSTot->getElementsByTagName("vNF")->item(0)->nodeValue, 2, ',', '.');
        $this->pdf->cell(($c1 * 2), $pequeno ? 5 : 6, "R$ {$vNF}", 1, 1, 'C', 1);

        if (isset($this->infAdic)) {
            $this->pdf->setFont('Arial', 'B', $pequeno ? 10 : 12);
            $this->pdf->cell(($c1 * 4), $pequeno ? 5 : 6, "DADOS ADICIONAIS", 1, 1, 'C', 1);
            $this->pdf->setFont('Arial', '', $pequeno ? 8 : 10);
            $this->pdf->multiCell(
                ($c1 * 4),
                $pequeno ? 3 : 5,
                "{$this->getTagValue($this->infAdic, "infCpl")}",
                1,
                1,
                'J',
                1
            );
        }
    }
}
