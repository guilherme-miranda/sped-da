<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\DA\MDFe\Damdfe;

<<<<<<< HEAD
$xml = file_get_contents(__DIR__ . '/fixtures/41211022545265000108580260000004081908511405.xml');
=======
>>>>>>> 1f7c4e60e6899ac7e670a4bc7927bb88bd8b4cbb
$logo = 'data://text/plain;base64,'. base64_encode(file_get_contents(realpath(__DIR__ . '/../images/tulipas.png')));
//$logo = realpath(__DIR__ . '/../images/tulipas.png');

$xml = base64_decode('PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz48bWRmZVByb2MgdmVyc2FvPSIzLjAwIiB4bWxucz0iaHR0cDovL3d3dy5wb3J0YWxmaXNjYWwuaW5mLmJyL21kZmUiPjxNREZlIHhtbG5zPSJodHRwOi8vd3d3LnBvcnRhbGZpc2NhbC5pbmYuYnIvbWRmZSI+PGluZk1ERmUgSWQ9Ik1ERmUzMTIyMDYwODU4MzYyOTAwMDExMzU4MDAxMDAwMDAwMDAxMTkwMjY4MjE3MyIgdmVyc2FvPSIzLjAwIj48aWRlPjxjVUY+MzE8L2NVRj48dHBBbWI+MjwvdHBBbWI+PHRwRW1pdD4yPC90cEVtaXQ+PG1vZD41ODwvbW9kPjxzZXJpZT4xPC9zZXJpZT48bk1ERj4xPC9uTURGPjxjTURGPjkwMjY4MjE3PC9jTURGPjxjRFY+MzwvY0RWPjxtb2RhbD4xPC9tb2RhbD48ZGhFbWk+MjAyMi0wNi0xNVQwOTowODo0Mi0wMDowMDwvZGhFbWk+PHRwRW1pcz4xPC90cEVtaXM+PHByb2NFbWk+MDwvcHJvY0VtaT48dmVyUHJvYz4xLjAuMDwvdmVyUHJvYz48VUZJbmk+TUc8L1VGSW5pPjxVRkZpbT5QQTwvVUZGaW0+PGluZk11bkNhcnJlZ2E+PGNNdW5DYXJyZWdhPjMxMDYyMDA8L2NNdW5DYXJyZWdhPjx4TXVuQ2FycmVnYT5CZWxvIEhPcml6b250ZTwveE11bkNhcnJlZ2E+PC9pbmZNdW5DYXJyZWdhPjxpbmZQZXJjdXJzbz48VUZQZXI+R088L1VGUGVyPjwvaW5mUGVyY3Vyc28+PGluZlBlcmN1cnNvPjxVRlBlcj5NVDwvVUZQZXI+PC9pbmZQZXJjdXJzbz48L2lkZT48ZW1pdD48Q05QSj4wODU4MzYyOTAwMDExMzwvQ05QSj48SUU+MDAxMDI5NTQ1MDAwMjwvSUU+PHhOb21lPkNPTkNFSVRPUyBDT01FUkNJTyBERSBBUlRJR09TIERFIFVTTyBDT01FUkNJQUwgTFREQTwveE5vbWU+PHhGYW50PkNPTkNFSVRPUyBNRURJQ0FMPC94RmFudD48ZW5kZXJFbWl0Pjx4TGdyPlIgQ0FTVEVMTyBTRVRVQkFMPC94TGdyPjxucm8+NDgwPC9ucm8+PHhCYWlycm8+Q0FTVEVMTzwveEJhaXJybz48Y011bj4zMTA2MjAwPC9jTXVuPjx4TXVuPkJFTE8gSE9SSVpPTlRFPC94TXVuPjxDRVA+MzEzMzAwOTA8L0NFUD48VUY+TUc8L1VGPjxmb25lPjMxMzQ3NDYzODE8L2ZvbmU+PGVtYWlsPmNvbnRhZ2VtZ0Bjb250YWdlbWcuY29tLmJyPC9lbWFpbD48L2VuZGVyRW1pdD48L2VtaXQ+PGluZk1vZGFsIHZlcnNhb01vZGFsPSIzLjAwIj48cm9kbz48aW5mQU5UVD48Uk5UUkM+NTc4MzgwNTU8L1JOVFJDPjwvaW5mQU5UVD48dmVpY1RyYWNhbz48Y0ludD4wMDAwMDAwMDE8L2NJbnQ+PHBsYWNhPlJGRzVDNjc8L3BsYWNhPjxSRU5BVkFNPjAxMjMzNDAyMzQ3PC9SRU5BVkFNPjx0YXJhPjEwMDAwPC90YXJhPjxjb25kdXRvcj48eE5vbWU+QUxHVVNUTyBBTFZFUyBQQVVMSU5PPC94Tm9tZT48Q1BGPjcxMDUwMTMyNjUzPC9DUEY+PC9jb25kdXRvcj48dHBSb2Q+MDI8L3RwUm9kPjx0cENhcj4wMjwvdHBDYXI+PFVGPk1HPC9VRj48L3ZlaWNUcmFjYW8+PC9yb2RvPjwvaW5mTW9kYWw+PGluZkRvYz48aW5mTXVuRGVzY2FyZ2E+PGNNdW5EZXNjYXJnYT4xNTAzNjA2PC9jTXVuRGVzY2FyZ2E+PHhNdW5EZXNjYXJnYT5JdGFpdHViYTwveE11bkRlc2NhcmdhPjxpbmZORmU+PGNoTkZlPjQyMjIwNDAxMDA1NzI4MDAxMTQwNTUwMDIwMDAwOTA5MTQxNTI3MTk0MTUyPC9jaE5GZT48L2luZk5GZT48L2luZk11bkRlc2NhcmdhPjwvaW5mRG9jPjx0b3Q+PHFORmU+MTwvcU5GZT48dkNhcmdhPjEwMC4wMDwvdkNhcmdhPjxjVW5pZD4wMTwvY1VuaWQ+PHFDYXJnYT4xNjAwLjAwMDA8L3FDYXJnYT48L3RvdD48aW5mQWRpYy8+PC9pbmZNREZlPjxpbmZNREZlU3VwbD48cXJDb2RNREZlPjwhW0NEQVRBW2h0dHBzOi8vZGZlLXBvcnRhbC5zdnJzLnJzLmdvdi5ici9tZGZlL3FyQ29kZT9jaE1ERmU9MzEyMjA2MDg1ODM2MjkwMDAxMTM1ODAwMTAwMDAwMDAwMTE5MDI2ODIxNzMmdHBBbWI9Ml1dPjwvcXJDb2RNREZlPjwvaW5mTURGZVN1cGw+PFNpZ25hdHVyZSB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC8wOS94bWxkc2lnIyI+PFNpZ25lZEluZm8+PENhbm9uaWNhbGl6YXRpb25NZXRob2QgQWxnb3JpdGhtPSJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy14bWwtYzE0bi0yMDAxMDMxNSIvPjxTaWduYXR1cmVNZXRob2QgQWxnb3JpdGhtPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwLzA5L3htbGRzaWcjcnNhLXNoYTEiLz48UmVmZXJlbmNlIFVSST0iI01ERmUzMTIyMDYwODU4MzYyOTAwMDExMzU4MDAxMDAwMDAwMDAxMTkwMjY4MjE3MyI+PFRyYW5zZm9ybXM+PFRyYW5zZm9ybSBBbGdvcml0aG09Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvMDkveG1sZHNpZyNlbnZlbG9wZWQtc2lnbmF0dXJlIi8+PFRyYW5zZm9ybSBBbGdvcml0aG09Imh0dHA6Ly93d3cudzMub3JnL1RSLzIwMDEvUkVDLXhtbC1jMTRuLTIwMDEwMzE1Ii8+PC9UcmFuc2Zvcm1zPjxEaWdlc3RNZXRob2QgQWxnb3JpdGhtPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwLzA5L3htbGRzaWcjc2hhMSIvPjxEaWdlc3RWYWx1ZT5VcVFDbDZxbTZqVWRnVkJSSVV3U1BaQWdiRE09PC9EaWdlc3RWYWx1ZT48L1JlZmVyZW5jZT48L1NpZ25lZEluZm8+PFNpZ25hdHVyZVZhbHVlPmh2clV6RzJqcVhmVDYySE9saGVNREZrUjVVb1YvK0Rxa0s5RnNkRWdZR1lrblBXQWx1dXU1cXBlckhPc2dFRGZyVjZyNGNDZy9MNitHQlhlS2RhSXZJRkhNdzYyR1pDUVhEeDdQY3ZNb3pIYS9kT1NyaVBGTW45RDBnTzkvZVUzS3lDMDZYcDJ6L2Rvd0pMYWN3MHN4ZURqR2M0czV4eEdLZytHSjZweSsvK1U0S3lYT3l1dXROdko4NGw4UExwVVZvRzNpakVKa3gwU1NpcmRDMVpLalFXanRQeE80TmxkQUZ1N2IySHBhRnZWNHJsSG4rQWFFM3FTM1FSKzlVa1lEaEkwZmxSSWpCRjNXVVNZZlp4M0NYREV2VVJlVHlSSFBBZE8xRktWTFNlV2Z4K0VQVGFYcmRlQm8wc1cvdFN6OHJTWE92Yit0ZjlTVFUxVTlucU5Cdz09PC9TaWduYXR1cmVWYWx1ZT48S2V5SW5mbz48WDUwOURhdGE+PFg1MDlDZXJ0aWZpY2F0ZT5NSUlIYXpDQ0JWT2dBd0lCQWdJSVdHc2lBaFpYeHpnd0RRWUpLb1pJaHZjTkFRRUxCUUF3V1RFTE1Ba0dBMVVFQmhNQ1FsSXhFekFSQmdOVkJBb1RDa2xEVUMxQ2NtRnphV3d4RlRBVEJnTlZCQXNUREVGRElGTlBURlZVU1NCMk5URWVNQndHQTFVRUF4TVZRVU1nVTA5TVZWUkpJRTExYkhScGNHeGhJSFkxTUI0WERUSXlNREl4TnpFeU5UY3dNRm9YRFRJek1ESXhOekV5TlRjd01Gb3dnZ0VETVFzd0NRWURWUVFHRXdKQ1VqRVRNQkVHQTFVRUNoTUtTVU5RTFVKeVlYTnBiREVMTUFrR0ExVUVDQk1DVFVjeEZ6QVZCZ05WQkFjVERrSmxiRzhnU0c5eWFYcHZiblJsTVI0d0hBWURWUVFMRXhWQlF5QlRUMHhWVkVrZ1RYVnNkR2x3YkdFZ2RqVXhGekFWQmdOVkJBc1REakkwTnpnek16STVNREF3TVRNME1Sa3dGd1lEVlFRTEV4QldhV1JsYjJOdmJtWmxjbVZ1WTJsaE1Sb3dHQVlEVlFRTEV4RkRaWEowYVdacFkyRmtieUJRU2lCQk1URkpNRWNHQTFVRUF4TkFRMDlPUTBWSlZFOVRJRU5QVFVWU1EwbFBJRVJGSUVGU1ZFbEhUMU1nUkVVZ1ZWTlBJRU5QVFVWU1EwbEJUQ0JNVkRvd09EVTRNell5T1RBd01ERXhNekNDQVNJd0RRWUpLb1pJaHZjTkFRRUJCUUFEZ2dFUEFEQ0NBUW9DZ2dFQkFMWmpYWDdwc0RzR2lpNThrWmUwZ3FoWi94MGRaczVubVZwQlB4YUd5NUFWNmw5SlR3RFgzdWovbFhhOUlCQVNGUGloamk2MFRCU3RCWXBTSU12L201a2xpN3FSZkhjb3lBZ3kzN3FMNlJ5UWMxSVRrYmdNUzJGTi9waXhTNlBiUXUrRWI4eWhHdFgzdS9OU2swYzJXeWxaeXUwNkdHSFJzY09PQXRLbDh2NFo4TE9ROHpsS2RETFA0VnAyMzFUaVppKzdMUEFnMUlmUWpSbE5reHh4NEFGTDJBM0dDNEFPMHR6ejhJRjdPazdYT1NJaXhBS2dwaFdiZUxxQmI1Ull1YVBzeXpDWU51Q21kOGNTOEtrakZUNWljczRUREJHM2o2ZFR2RTZUMTdvM2lGcjVuOEU5SUJBclRxcGtXWFUxa2JWbHk3TXU5TjZPY0RmYUtVUVNuaWtDQXdFQUFhT0NBb2t3Z2dLRk1Ba0dBMVVkRXdRQ01BQXdId1lEVlIwakJCZ3dGb0FVeFZMdEpZQUozNXlDeUo5SHh0MjBYekhkdWJFd1ZBWUlLd1lCQlFVSEFRRUVTREJHTUVRR0NDc0dBUVVGQnpBQ2hqaG9kSFJ3T2k4dlkyTmtMbUZqYzI5c2RYUnBMbU52YlM1aWNpOXNZM0l2WVdNdGMyOXNkWFJwTFcxMWJIUnBjR3hoTFhZMUxuQTNZakNCeEFZRFZSMFJCSUc4TUlHNWdTTmhaRzFwYm1semRISmhkR2wyYjBCamIyNWpaV2wwYjNOc2IycGhMbU52YlM1aWNxQWtCZ1ZnVEFFREFxQWJFeGxGVFVWU1UwOU9JRVJGSUU5TVNWWkZTVkpCSUVGQ1FVUkZvQmtHQldCTUFRTURvQkFURGpBNE5UZ3pOakk1TURBd01URXpvRGdHQldCTUFRTUVvQzhUTFRJeU1ESXhPVGMzTURNMU5qSXpOREEyTVRNd01EQXdNREF3TURBd01EQXdNREF3TURBd01EQXdNREF3TUtBWEJnVmdUQUVEQjZBT0V3d3dNREF3TURBd01EQXdNREF3WFFZRFZSMGdCRll3VkRCU0JnWmdUQUVDQVNZd1NEQkdCZ2dyQmdFRkJRY0NBUlk2YUhSMGNEb3ZMMk5qWkM1aFkzTnZiSFYwYVM1amIyMHVZbkl2Wkc5amN5OWtjR010WVdNdGMyOXNkWFJwTFcxMWJIUnBjR3hoTG5Ca1pqQWRCZ05WSFNVRUZqQVVCZ2dyQmdFRkJRY0RBZ1lJS3dZQkJRVUhBd1F3Z1l3R0ExVWRId1NCaERDQmdUQStvRHlnT29ZNGFIUjBjRG92TDJOalpDNWhZM052YkhWMGFTNWpiMjB1WW5JdmJHTnlMMkZqTFhOdmJIVjBhUzF0ZFd4MGFYQnNZUzEyTlM1amNtd3dQNkE5b0R1R09XaDBkSEE2THk5alkyUXlMbUZqYzI5c2RYUnBMbU52YlM1aWNpOXNZM0l2WVdNdGMyOXNkWFJwTFcxMWJIUnBjR3hoTFhZMUxtTnliREFkQmdOVkhRNEVGZ1FVZXRzWUtEcEx5bTl5SkxVVGNYTUxCNFY2WVZRd0RnWURWUjBQQVFIL0JBUURBZ1hnTUEwR0NTcUdTSWIzRFFFQkN3VUFBNElDQVFBRWxTNk92TW5xWHB3L2hMM1VrakpXVVQ1S043WmdEekY1cithQVJlM2l2TWNnS2NIbEdTazl0Vit1V2lWNm1SdzJUeGlSSmZoUUx0aUJTK1RuYzNxd0puNmx5djZIMUZlZy9yYVJ1dzRDQjhvU3dwaVptK1YrRXpsRkNBelRhYjhBY3YxWEtSSzF3aEdYaTd4MHIyenlnd3h6dllyeTJpOC9Ia01lbGdidnBQUDFzbW44dXJYZ1F4MkNESkRwNXBLcEFLRmhzWjM4VHdnTFEyTmpXNGtzcWtnRG51aXRBN1l1SzZOd21yZTBDMmRuQTJXWEpjekxiQ1JnSEFOVWs0SHU5VENHSDRFaXBxclUzMTd1WGJTY2RKMkdkY3BXWjByMWViMDZSSWVVYjIxWEs3VURxQVVzUm1zQTBnczZRZWNXRGQ5SktqeHRIckxOZGxSZEVydWk2UWkycFpQakVycmZyR00zNEFRcWpRYVFCdDBaaVNWZURyd0ZUbERBU2V1Zkh3S0ZISG5uRHU3bWxoT2ltY0ZZS2NxVm5xRDZnd1ozcm44elFZeFdocUpDOWpVejlmT1lVU3NQNlR0YmtTcHErbkREcXNQbkVIMEJoQ1BQMEp2VE5IT0w1VzUvZ3NxR2kvYjhwWVFyUjc4QVh0eXd0MUMrd1BzQXJtTXZlR3NOcUIvY2dVa3NvYWkyMFVBSHZHTWFrdnZGaEdSWVJ5cmp6QVRpVXN6UUtoRG5yY1JJaU9rSXhuS2NsT0tkVnFaN2t2enhKcTlPYWtxNnA1TmVNaG5GZ09FUVh0ZmlweVhQaFFOeGNJaUF5OFN2NVg0b1dTRWpzMi82SmhHRDkvYTlCUklpWnJ1bFhkLzQ1bjZRdkZxWXBKdEU2MTZOZnF4Q2MxY3JjcE9lUUE9PTwvWDUwOUNlcnRpZmljYXRlPjwvWDUwOURhdGE+PC9LZXlJbmZvPjwvU2lnbmF0dXJlPjwvTURGZT48cHJvdE1ERmUgeG1sbnM9Imh0dHA6Ly93d3cucG9ydGFsZmlzY2FsLmluZi5ici9tZGZlIiB2ZXJzYW89IjMuMDAiPjxpbmZQcm90IElkPSJNREZlOTMxMjIwMDAwMDE3NzczIj48dHBBbWI+MjwvdHBBbWI+PHZlckFwbGljPlJTMjAyMjA2MDIxNzAwMTQ8L3ZlckFwbGljPjxjaE1ERmU+MzEyMjA2MDg1ODM2MjkwMDAxMTM1ODAwMTAwMDAwMDAwMTE5MDI2ODIxNzM8L2NoTURGZT48ZGhSZWNidG8+MjAyMi0wNi0xNVQwODoyODowOS0wMzowMDwvZGhSZWNidG8+PG5Qcm90PjkzMTIyMDAwMDAxNzc3MzwvblByb3Q+PGRpZ1ZhbD5VcVFDbDZxbTZqVWRnVkJSSVV3U1BaQWdiRE09PC9kaWdWYWw+PGNTdGF0PjEwMDwvY1N0YXQ+PHhNb3Rpdm8+QXV0b3JpemFkbyBvIHVzbyBkbyBNREYtZTwveE1vdGl2bz48L2luZlByb3Q+PC9wcm90TURGZT48L21kZmVQcm9jPg==');
$xml = file_get_contents(__DIR__ . '/fixtures/41211022545265000108580260000004081908511405.xml');

try {
    $damdfe = new Damdfe($xml);
    $damdfe->debugMode(true);
    $damdfe->creditsIntegratorFooter('WEBNFe Sistemas - http://www.webenf.com.br');
    $damdfe->printParameters('L');
    $pdf = $damdfe->render($logo);
    header('Content-Type: application/pdf');
    echo $pdf;
} catch (Exception $e) {
    echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
}
