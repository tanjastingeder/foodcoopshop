<?php
/**
 * FoodCoopShop - The open source software for your foodcoop
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since         FoodCoopShop 1.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 * @author        Mario Rothauer <office@foodcoopshop.com>
 * @copyright     Copyright (c) Mario Rothauer, http://www.rothauer-it.com
 * @link          https://www.foodcoopshop.com
 */
?>
<h1>Nutzungsbedingungen der Plattform</h1> 

<p>(im Folgenden kurz: FoodCoopShop)</p>

<h2>1. Geltung der AGB</h2>

<p>1.1. Für alle gegenwärtigen und zukünftigen Leistungen, die der FoodCoopShop im Rahmen seiner Internet-Dienstleistung unter der Domain <?php echo Configure::read('app.cakeServerName'); ?> für seine Nutzer erbringt (im Folgenden gemeinsam kurz: die Leistung), gelten ausschließlich die nachfolgenden Bedingungen.</p> 

<p>1.2. Geschäftsbedingungen des Nutzers kommen nicht zur Anwendung.</p>

<h2>2. Leistungen und Entgelte</h2>

<p>2.1. Der FoodCoopShop stellt dem Nutzer eine Plattform unentgeltlich zur Verfügung, auf der Anbieter Waren und Dienstleistungen präsentieren. Diese dargebotenen Waren und Dienstleistungen sind eine unverbindliche Aufforderung des jeweils genannten Lieferanten an den Nutzer, ein verbindliches Anbot für die angebotenen Waren und Dienstleistungen zu legen. Durch die Bestellung legt der Nutzer ein solches verbindliches Anbot an den jeweils genannten Lieferanten. Ein Vertrag zwischen dem Nutzer und dem Lieferanten kommt zustande, wenn der Lieferant mit der Leistungserbringung begonnen hat oder die Waren zur Abholung bereitgelegt hat.</p>

<p>2.2. Der Vertrag über die Waren und Dienstleistungen kommt ausschließlich zwischen dem Nutzer und dem jeweiligen Lieferanten zustande.</p>

<p>2.3. Die auf der Website angegebenen Preise verstehen sich inklusive der gesetzlichen Steuer, jedoch exklusive der Verpackungs- und Versandkosten. Allfällige weitere Kosten (etwa Pfand) sind gesondert ausgewiesen.</p> 

<p>2.4. Vor Abgabe der Vertragserklärung werden die Gesamtkosten dargestellt.</p>

<h2>3. Schadenersatz und Gewährleistung</h2>

<p>3.1. Die Nutzung des FoodCoopShop ist für die Nutzer kostenlos. Die Haftung des FoodCoopShop ist daher ausgeschlossen.</p> 

<p>3.2. Für Schäden infolge schuldhafter Vertragsverletzung haftet der FoodCoopShop bei eigenem Verschulden oder dem eines Erfüllungsgehilfen nur für Vorsatz oder grobe Fahrlässigkeit. Dies gilt nicht für Schäden an der Person.</p> 

<h2>4. Rücktrittsrecht</h2>

<p>4.1. Der Nutzer schließt den Vertrag mit dem jeweiligen Lieferanten direkt. Der Nutzer erhält Informationen über das Rücktrittsrecht <a href="/Informationen-ueber-Ruecktrittsrecht.pdf" target="_blank">hier</a>. Grundsätzlich ist das Rücktrittsrecht für die Lieferung von Lebensmittel ausgeschlossen.</p> 

<p>4.2. Der jeweilige Lieferant wird von den alternativen Streitbeilegungsstellen „Online-Streitbeilegung“ (https://webgate.ec.europa.eu/odr) sowie „Internetombudsmann“ (www.ombudsmann.at) erfasst. Der Nutzer hat auf den genannten Plattformen die Möglichkeit, außergerichtliche Streitbeilegung durch eine unparteiische Schlichtungsstelle in Anspruch zu nehmen.</p> 

<p>Die E-Mailadresse des jeweiligen Lieferanten ergibt sich aus dessen Impressum.</p>

<h2>5. Schlussbestimmungen</h2> 

<p>5.1. Erfüllungsort für alle Leistungen aus diesem Vertrag ist <?php echo implode(', ', $this->Html->getAddressFromAddressConfiguration()); ?>.</p> 

<p>5.2. Für Rechtsstreitigkeiten aus diesem Vertrag gilt ausschließlich österreichisches Recht. Die Anwendung des UN-Kaufrechts, der Verweisungsnormen des IPRG und der VO (EG) Nr. 593/2008 des Europäischen Parlaments und des Rates vom 17. Juni 2008 über das auf vertragliche Schuldverhältnisse anzuwendende Recht (Rom I-Verordnung) ist ausgeschlossen.</p> 

<p>5.3. Änderungen oder Ergänzungen dieser Nutzungsbedingungen bedürfen zu ihrer Wirksamkeit der Schriftform.</p>

<?php if ($this->Html->paymentIsCashless()) { ?>
<h2>6. Guthabenkonto</h2>

<p>6.1. Sämtliche Leistungen werden von einem Guthabenkonto abgebucht. Das Guthabenkonto wird vom FoodCoopShop verwaltet. Der Nutzer kann jederzeit auf das Guthabenkonto bis zu einem Maximalbetrag von EUR 500,00 Beträge einbezahlen. Bei einem negativen Kontostand sind weitere Bestellungen nicht möglich.</p> 

<p>6.2. Durch die Bezahlung der einzelnen Waren ermächtigt der Nutzer den FoodCoopShop, nach Abgabe der Bestellung den jeweils angegebenen Betrag an den Lieferanten zu bezahlen.</p>

<p>6.3. Der Nutzer hat jederzeit das Recht, die Auszahlung des Guthabenkontos zu verlangen, der FoodCoopShop wird die Auszahlung binnen 14 Tagen auf das vom Nutzer bekanntgegebene Konto mittels Überweisung durchführen.</p> 
<?php } ?>
