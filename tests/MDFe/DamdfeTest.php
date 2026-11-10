<?php

namespace NFePHP\DA\Tests\MDFe;

use NFePHP\DA\MDFe\Damdfe;
use NFePHP\DA\Tests\Utils;
use PHPUnit\Framework\TestCase;

class DamdfeTest extends TestCase
{
    public function test_imprimir_damdfe(): void
    {
        $pathXml = TEST_FIXTURES . 'xml' . DIRECTORY_SEPARATOR . 'mdfe_modelo_nao_valido.xml';
        $pathPdf = TEST_FIXTURES . 'pdf' . DIRECTORY_SEPARATOR;
        $pdfName = 'mdfe_modelo_nao_valido.pdf';

        if (file_exists($pathPdf . $pdfName)) {
            unlink($pathPdf . $pdfName);
        }

        $xmlFile = file_get_contents($pathXml);

        $damdfe = new Damdfe($xmlFile, 'P', 'A4', '', 'F', $pathPdf);
        $pdfContent = $damdfe->render();

        if ($pdfContent) {
            file_put_contents($pathPdf . $pdfName, $pdfContent);
        }

        $this->assertFileExists($pathPdf . $pdfName);
    }

    /**
     * Os documentos da MDF-e (NF-e, CT-e e outras MDF-e) não devem aparecer quando a MDF-e foi autorizada
     */
    public function test_nao_incluir_documentos(): void
    {
        $damdfe = new Damdfe(file_get_contents(TEST_FIXTURES . 'xml/mdfe.xml'));
        $damdfe->setExibirDocumentosVinculados(false);
        $pdf = $damdfe->render();
        $this->assertFalse(Utils::pdfContemTexto($pdf, '53250509231544000139550010000568821095071500'));
    }

    /**
     * Os documentos da MDF-e (NF-e, CT-e e outras MDF-e) devem aparecer quando a MDF-e foi emitida em contingência
     */
    public function test_incluir_documentos_emitida_em_contingencia(): void
    {
        $damdfe = new Damdfe(file_get_contents(TEST_FIXTURES . 'xml/mdfe_contingencia.xml'));
        $pdf = $damdfe->render();
        $this->assertTrue(Utils::pdfContemTexto($pdf, '53250509231544000139550010000568821095071500'));
    }

    /**
     * O schema do vale-pedágio aceita CNPJPg ou CPFPg. A coluna do responsável imprime o que vier.
     */
    public function test_vale_pedagio_imprime_cnpj_ou_cpf_do_pagador(): void
    {
        $xml = file_get_contents(TEST_FIXTURES . 'xml/mdfe.xml');
        $xml = str_replace(
            '<infANTT/>',
            '<infANTT><valePed>'
            . '<disp><CNPJForn>11222333000181</CNPJForn><CNPJPg>44555666000199</CNPJPg><nCompra>222</nCompra></disp>'
            . '<disp><CNPJForn>11222333000181</CNPJForn><CPFPg>52998224725</CPFPg><nCompra>333</nCompra></disp>'
            . '</valePed></infANTT>',
            $xml
        );

        $pdf = (new Damdfe($xml))->render();

        $this->assertTrue(Utils::pdfContemTexto($pdf, '44555666000199'));
        $this->assertTrue(Utils::pdfContemTexto($pdf, '52998224725'));
    }
}
