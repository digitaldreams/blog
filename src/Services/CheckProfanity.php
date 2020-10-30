<?php

namespace Blog\Services;

use Blog\Models\Category;
use Blog\Models\Post;
use Blog\Models\Tag;
use Illuminate\Database\Eloquent\Model;

class CheckProfanity
{
    /**
     * @var Model
     */
    private $model;

    protected $map = [
        Post::class => [
            'title',
            'body',
            'slug',
        ],
        Category::class => [
            'title',
            'slug',
        ],
        Tag::class => [
            'slug',
            'name',
            'description',
        ],
    ];

    protected $words = 'acrotomophilia|aeolus|ahole|alabama hot pocket|alaskan pipeline|anal|anal impaler|anal leakage|analprobe|anilingus|anus|apeshit|ar5e|areola|areole|arian|arrse|arse|arsehole|aryan|ass|ass|fuck|ass fuck|ass hole|assbag|assbandit|assbang|assbanged|assbanger|assbangs|assbite|assclown|asscock|asscracker|asses|assface|assfaces|assfuck|assfucker|ass|fucker|assfukka|assgoblin|assh0le|asshat|ass|asshead|assho1e|asshole|assholes|asshopper|ass|jabber|assjacker|asslick|asslicker|assmaster|assmonkey|assmucus|assmucus|assmunch|assmuncher|assnigger|asspirate|ass| assshit|assshole|asssucker|asswad|asswhole|asswipe|asswipes|erotic|autoerotic|axwound|azazel|azz|b|tch|b00bs|b17ch|b1tch|babeland|batter|ball|gag||licking|sucking|ballbag|ballsack|bampot|bangbros|bareback|barenaked|barf|bastard|bastardo|bastards|bastinado|batty|bawdy|bbw|bdsm|beaner|beaners|beardedclam|beastial|beastiality|beatch|beaver|beaver|cleaver|beaver|beeyotch|bellend|bender|beotch|bescumber|bestial|bestiality|b|+ch|biatch|big black|big breasts|big knockers|big tits|bigtits|bimbo|bimbos|bint|birdlock|bitch|bitch tit|bitch tit|bitchass|bitched|bitcher|bitchers|bitches|bitchin|bitching|bitchtits|bitchy|black|cock|bloodclaat|bloody|bloody|hell|blow job|blow|blow|blowjob|blowjobs|blumpkin|blumpkin|bod|bodily|boink|boiolas|bollock|bollocks|bollok|bollox|bondage|boned|boner|boners|bong|boob|boobies|boobs|booby|booger|bookie|boong|booobs|boooobs|booooobs|booooooobs|bootee|bootie|booty|booty| boozer|boozy|bosom|bosomy|breasts| brotherfucker| buceta|bugger|bukkake|bull|shit|bulldyke|bullet| bullshit|bullshits|bullshitted|bullturds|bum|bum boy|bumblefuck|bumclat|bummer|buncombe|bung|bung| bunghole|bunny|fucker| bust| busty|butt|butt|fuck|butt|fuck|butt| buttcheeks|buttfuck|buttfucka|buttfucker|butthole|buttmuch|buttmunch|butt| |buttplug|c.0.c.k|c.o.c.k.|c.u.n.t|c0ck|c-0|c|k|c0cksucker|caca|cacafuego|cahone|camel|toe|cameltoe|camgirl|camslut|camwhore|muncher|carpetmuncher|cawk|cervix|chesticle|chi|chi|chick with a dick|fucker|chinc|chincs|chink|chinky|choad|choade|choade|rosebuds|chode|chodes|chota|chota|bags|cipa|circlejerk|cl1t|cleveland|climax|clit|clit|licker|clit|licker|clitface|clitfuck|clitoris|clitorus|clits|clitty|clitty|clitty|litter|clover|clamps|clunge|clusterfuck|cnut|cocain|cocaine|coccydynia|cock|c|o|c|k|cock|cock|cock|snot|cock|snot|cock|sucker|cockass|cockbite|cockblock|cockburger|cockeye|cockface|cockfucker|cockhead|cockholster|cockjockey|cockknocker|cockknoker|Cocklump|cockmaster|cockmongler|cockmongruel|cockmonkey|cockmunch|cockmuncher|cocknose|cocknugget|cocks|cockshit|cocksmith|cocksmoke|cocksmoker|cocksniffer|cocksuck|cocksuck|cocksucked|cocksucked|cocksucker|cock|sucker|cocksuckers|cocksucking|cocksucks|cocksucks|cocksuka|cocksukka|cockwaffle|dodger|coital|cok|cokmuncher|coksucka|commie|condom|coochie|coochy|coon|coonnass|coons|cooter|coprolagnia|coprophilia|corksucker|cornhole|cornhole|corp|whore|corp|whore|corpulent|cox|crabs| crackwhore|crap|crappy|creampie|cretin|crikey|cripple|crotte|cum|cum|chugger|cum|chugger|cum|dumpster|cum|dumpster|cum|freak|cum|freak|cum|guzzler|cum|guzzler|cumbubble|cumdump|cumdump|cumdumpster|cumguzzler|cumjockey|cummer|cummin|cumming|cums|cumshot|cumshots|cumslut|cumstain|cumtart|cunilingus|cunillingus|cunnie|cunnilingus|cunny|cunt|cunt|cunt|cuntass|cuntbag|cuntbag|cuntface|cunthole|cunthunter|cuntlick|cuntlick|cuntlicker|cuntlicker|cuntlicking|cuntlicking|cuntrag|cunts|cuntsicle|cuntsicle|cuntslut|cunt|cunt| cus|cyalis|cyberfuc|cyberfuck|cyberfuck|cyberfucked|cyberfucked|cyberfucker|cyberfuckers|cyberfucking|cyberfucking|d0ng|d0uch3|d0uche|d1ck|d1ld0|d1ldo|dago|dagos|darkie|rape|daterape|dawgie|throat|deepthroat|deggo|dendrophilia|dick|dick|dick|dick|dick|dick|dickbag|dickbeaters|dickdipper|dickface|dickflipper|dickfuck|dickfucker|dickhead|dickheads|dickhole|dickish|dick|ish|dickjuice|dickmilk|dickmonger|dickripper|dicks|dicksipper|dickslap|dick|dicksucker|dicksucking|dicktickler|dickwad|dickweasel|dickweed|dickwhipper|dickwod|dickzipper|diddle|dike|dildo|dildos|diligaf|dillweed|dimwit|dingle|dingleberries|dingleberry|dink|dinks|dipship|dipshit|dirsa|sanchez|Sanchez|div|dlck|dogstyle|dog|fucker|doggiestyle|doggie style|doggin|dogging|doggy style|doggystyle|dolcett|domination|dominatrix|dommes|dong|donkey|donkeypunch|donkeyribber|doochbag|doofus|dookie|doosh|dopey|dong|penetration|Doublelift|douch3|douche|douchebag|douchebags|douche|fag|douchewaffle|douchey|dp|hump|duche|dumass|dumb|ass|dumbass|dumbasses|Dumbcunt|dumbfuck|dumbshit|dumshit|dvda|dyke|dykes|eat a dick |eat my ass|ecchi|ejaculate|ejaculated|ejaculates|ejaculates|ejaculating|ejaculating|ejaculatings|ejaculation|ejakulate|erect|erection|erotic|erotism|escort|essohbee||eunuch|extacy|extasy|fu|c|k|f|u|c|k|e|r|f.u.c.k|f_u_c_k|f4nny|fack|fag|fagbag|fagfucker|fagg|fagged|fagging|faggit|faggitt|faggot|faggotcock|faggots|faggs|fagot|fagots|fags|fagtard|faig|faigt|fanny|fannybandit|fannyflaps|fannyfucker|fanyy|fart|fartknocker|fatass|fcuk|fcuker|fcuking|fecal|feck|fecker|feist|felch|felcher|felching|fellate|fellatio|feltch|feltcher|femdom|fenian|fice|figging|fingerbang|fingerfuck|fingerfuck|fingerfucked|fingerfucked|fingerfucker|fingerfucker|fingerfuckers|fingerfucking|fingerfucking|fingerfucks|fingerfucks|fingering|fuck|fist|fuck|fisted|fistfuck|fistfucked|fistfucked|fistfucker|fistfucker|fistfuckers|fistfuckers|fistfucking|fistfucking|fistfuckings|fistfuckings|fistfucks|fistfucks|fisting|fisty|flamer|flange|fleshflute|flog|flog|floozy|foad|foah|fondle|foobar|fook|fooker|foot|fetish|footjob|foreskin|freex|frenchify|frigg|frigga|frotting|fubar|fuc|fuck|fuck|f|u|c|k|f u c k|fuck|fuck|fuck|Fuck off|fuck|fuck|fuck|fuck|rophy|fuck|yo|mama|fuck|yo|mama|fuck fucka|fuckass|fuck|ass|fuck|ass|fuckbag|fuck|bitch|fuck|bitch|fuckboy|fuckbrain|fuckbutt|fuckbutter|fucked|fuckedup|fucker|fuckers|fuckersucker|fuckface|fuckhead|fuckheads|fuckhole|fuckin|fucking|fuckings|fuckingshitmotherfucker|fuckme|fuckme|fuckmeat|fuckmeat|fucknugget|fucknut|fucknutt|fuckoff|fucks|fuckstick|fucktard|fuck|tard|fucktards|fucktart|fucktoy|fucktoy|fucktwat|fuckup|fuckwad|fuckwhit|fuckwit|fuckwitt|fudge|packer|fudgepacker|fudge|packer|fuk|fuker|fukker|fukkers|fukkin|fuks|fukwhit|fukwit|fuq|futanari|fux|fux0r|fvck|fxck|gae|gai|gangbang|gangbanged|gangbangs|ganja|gash|gassy|ass|gassy|ass|gay|ay|sex|gayass|gaybob|gaydo|gayfuck|gayfuckist|gaylord|gaysex|gaytard|gaywad|bender|genitals|gey|gfy|ghay|ghey|cock|gigolo|gippo|git|glans|goatcx|goatse|god|god|damn|godamn|godamnit|goddam|goddammit|goddamn|goddamned|damned|goddamnit|godsdamn|gokkun|goldenshower|golliwog|gonad|gonads|goo|gooch|goodpoop|gook|gooks|goregasm|gringo|grope|sex|gspot|g|gtfo|guido|guro|h0m0|h0mo |handjob|hardcore|hardcoresex|he11|hebe|heeb|hemp|hentai|heroin|herp|herpes|herpy|heshe|hircismus|hitler|hiv|ho|hoar|hoare|hobag|hoe|hoer|shit|hom0|homey|homo|homodumbshit|homoerotic|homoey|honkey|honky|hooch|hookah|hooker|hoor|hootch|hooter|hooters|hore|horniest|horny|hotsex|kill|murdep|murder|hump|humped|humping|hun|hussy|hymen|iap|iberian|inbred|incest|injun|intercoursejackass|jackasses|jackhole|jackoff|jaggi|jagoff|jail|bait|jailbait|jap|japs|jerk|jerk|jerk0ff|jerkass|jerked|jerkoff|jerk|jigaboo|jiggaboo|jiggerboo|jism|jiz|jiz|jizm|jizm|jizz|jizzed|juggs|junglebunny|junkie|junky|kafir|kawk|kike|kikes|kill|kinbaku|kinkster|kinky|klan|knobbing|knobead|knobed|knobend|knobhead|knobjocky|knobjokey|kock|kondum|kondums|kooch|kooches|kootch|kraut|kum|kummer|kumming|kums|kunilingus|kunja|kunt|kwif|kwif|kyke|l3|ch|l3itch|labia|lameass|lardass|lech|LEN|leper|lesbian|lesbians|lesbo|lesbos|lez|lezza|lesbo|lezzie|lmao|lmfao|loin|loins|lolita|looney|lovemaking|lube|lust|lusting|lusty|m0f0|m0fo|m45terbate|ma5terb8|ma5terbate|mafugly|mafugly|squirting|mams|masochist|massa|masterb8|masterbat*|masterbat3|masterbate|masterbating|masterbation|masterbations|masturbate|masturbating|masturbation|maxi|mcfagget|menage|trois|menses|menstruate|menstruation|meth|m|fucking|mick|microphallus|midget|milf|minge|minger|mof0|mofo|mo|fo|molest|mong|moo|moo|foo|foo|moolie|moron|mothafuck|mothafucka|othafuckas|mothafuckaz|mothafucked|mothafucked|mothafucker|mothafuckers|mothafuckin|mothafucking|mothafucking|mothafuckings|mothafucks|fucker|fucker|motherfuck|motherfucka||motherfucked|motherfucker|motherfuckers|motherfuckin|motherfucking|motherfuckings|motherfuckka|motherfucks|muff|muff|diver|muff|muff|puff|muffdiver|muffdiving|munging|munter|murder|mutha|muthafecker|muthafuckker|muther|mutherfucker|n1gga|n1gger|nambla|napalm|nawashi|nazi|nazism|dick|dick|negro|neonazi|nig|nog|nigaboo|nigg3r|nigg4h|nigga|niggah|niggas|niggaz|nigger|niggers|niggle|niglet|nig|nog|nimphomania|nimrod|ninny|ninnyhammer|nipple|nipples|nob|nob|jokey|nobhead|nobjocky|nobjokey|nonce|nsfw|numbnuts|nutsack|nutter|nympho|nymphomania|octopussy|omorashi|opiate|opium|orally|orgasim|orgasims|orgasm|orgasmic|orgasms|orgies|orgy|ovary|ovum|ovums|p.u.s.s.y.|p0rn|paedophile|paki|panooch|pansy|pawn|pcp|pecker|peckerhead|pedo|pedobear|pedophile|pedophilia|pedophiliac|pee|peepee|pegging|penetrate|penetration|penial|penile|penis|penisbanger|penisfucker|penispuffer|perversion|phallic|sex|phonesex|phuck|phuk|phuked|phuking|phukked|phukking|phuks|phuq|shit|pigfucker|pikey|pillowbiter|pimp|pimpis|pinko|piss|piss|pig|pissed|pissed|pisser|pissers|pisses|pisses|pissflaps|pissin|pissin|pissing|pissoff|pissoff|piss|pisspig|playboy|polack|polesmoker|pollock|ponyplay|poof|poon|poonani|poonany|poontang|poop|poop|poopchute|Poopuncher|porchmonkey|porn|porno|pornography|pornos|prick|pricks|prickteaser|prig|prod|pron|prostitute|prude|psycho|pthc|pube|pubes|pubic|pubis|punani|punanny|punany|punkass|punky|punta|puss|pusse|pussi|pussies|pussy|pussy|fart|pussy|fart|pussy|pussy|pussylicking|pussypounder|pussys|pust|puto|queaf|queaf|queef|queerbait|queerhole|queero|queers|quim|racy|raghead|boner|rape|raped|raper|rapey|raping|rapist|raunch|rectal|rectum|rectus|reefer|reetard|reich|renob|retard|retarded|cowgirl|rimjaw|rimjob|rimming|ritard|rosy|nd|rtard |tard|rumprammer|ruski|trombone|s&m|s.h.i.t.|s.o.b.|s_h_i_t|s0b|sadism|sadist|sambo|nigger|sandbar|sandnigger|sanger|santorum|scag|scantily|scat|schizo|schlong|scissoring|screwed|screwing|scroat|scrog|scrot|scrote|scrotum|scrud|scum|seaman|seamen|seks|semen|sex|sexo|sexual|sexy|sh|+|sh!t|sh1t|s-h-1-t|shag|shagger|shaggin|shagging|shamedame|shaved beaver|shaved pussy|shemale|shi+|shibari|shirt lifter|shit|s-h-i-t|shit ass|shit fucker|shit fucker|shitass|shitbag|shitbagger|shitblimp|shitbrains|shitbreath|shitcanned|shitcunt|shitdick|shite|shiteater|shited|shitey|shitface|shitfaced|shitfuck|shitfull|shithead|shitheads|shithole|shithouse|shiting|shitings|shits|shitspitter|shitstain|shitt|shitted|shitter|shitters|shitters|shittier|shittiest|shitting|shittings|shitty|shiz|shiznit|shota|shrimping|sissy|skag|skank|skeet|skullfuck|slag|slanteye|slave|slut|slut|slut|slutbag|slutdumper|slutkiss|sluts|smartass|smartasses|smegma|smut|smutty|snowballing|snuff|sod|sodom|sodomize|sodomy|son of a bitch|son of a motherless|son of a whore|son of a bitch|souse|soused|spac|sperm|spic|spick|spik|spiks|splooge|splooge|spooge|spook|spunk|stfu|stiffy|stoned|strapon|strappado|doggy|suck|suckass|sucked|sucking|sucks|sumofabiatch|swastika|swinger|t1t|t1tt1e5|t1tties|taff|taig|tainted|piss|tampon|tard|tawdry|bagging|teabagging|teat|teets|teez|teste|testee|testes|testical|testicle|testis|threesome|throating|thundercunt|tinkle|tit|tit|wank|tit|wank|titfuck|titi|tities|tits|titt|tittie5|tittiefucker|titties|titty|tittyfuck|tittyfucker|tittywank|titwank|toke|tongue|toots|topless|tosser|towelhead|tramp|tranny|transsexual|tribadism|trumped|tubgirl|turd|tush|tushy|tw4t|twat|twathead|twatlips|twats|twatty|twatwaffle|twink|twinkie|tongue|twunt|twunter|unclefucker|undies|unwed|upskirt|urethra|urine|urophilia|uterus|uzi|v14gra|v1gra|vag|vagina|vajayjay|va||veqtable|viagra|vibrator|vixen|vjayjay|vomit|vorarephilia|voyeur|vulva|w00se|wad|wang|wank|wanker|wankjob|wanky|wazoo|wedgie|weenie|weewee|weiner|weirdo|wench|wetback|wh0re|wh0reface|whiz|whoar|whoralicious|whore|whorealicious|whorebag|whored|whoreface|whorehopper|whorehouse|whores|whoring|wigger|willies|willy|licker|wiseass|wiseasses|wog|womb|wop|wtf|xrated|x|xx|xxx|yaoi|yeasty|yid|yiffy|yobbo|zibbi|zoophilia|zubb';

    /**
     * CheckProfanity constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return bool
     */
    public function check()
    {
        $class = get_class($this->model);
        if (isset($this->map[$class])) {
            foreach ($this->map[$class] as $column) {
                $foundWords = $this->checkString(strip_tags(strtolower($this->model->$column)));

                if (count($foundWords) > 0) {
                    session()->flash('error', 'profanity words [' . implode(', ', $foundWords) . ']  found on ' . $column . '  ');

                    return true;
                    break;
                }
            }
        }

        return false;
    }

    /**
     * @param $string
     *
     * @return array
     */
    public function checkString($string)
    {
        $trimmed_array = array_map('trim', explode('|', $this->words));
        $reviewarray = explode(' ', $string);
        $reviewarr = array_map('trim', $reviewarray);

        return array_filter(array_unique(array_intersect($trimmed_array, $reviewarr)));
    }
}
