let ranksInItalics = [
    'genus',
    'species',
    'subspecies',
    'variety',
    'forma',
    'nothosubspecies',
    'nothovariety',
    'nothoforma',
    'cultivar'
];

let prefixes = new Map();
prefixes.set('subspecies', 'subsp.');
prefixes.set('variety', 'var.');
prefixes.set('forma', 'f.');
prefixes.set('nothosubspecies', 'nothosubsp.');
prefixes.set('nothovariety', 'nothovar.');
prefixes.set('nothoforma', 'nothof.');

let formatName = function(taxon, includeAuthor) {
    let rank = null;
    let italics = '';

    if (taxon) {
        let formattedName = '';

        if (taxon.type === 'Taxon') {
            rank = taxon.taxonRank.name;

            if (ranksInItalics.indexOf(rank) > -1) {
                italics = ' italics';
            }
        }
        else if (taxon.type.toLowerCase() === 'cultivar') {
            if (typeof taxon.taxon !== 'undefined') {
                rank = taxon.taxon.taxonRank.name;
            }
            else if (taxon.taxonRank) {
                rank = taxon.taxonRank
            }
            italics = ' italics';
        }

        if (typeof taxon.taxonomicStatus !== 'undefined') {
            if (taxon.taxonomicStatus.name === 'accepted') {
                formattedName += '<span class="currentname' + italics + '">';
            }
            else {
                formattedName += '<span class="oldname' + italics + '">';
            }
        }

        let name = '<span class="namebit">' + taxon.name.scientificName + '</span>';

        let pre = [...prefixes.keys()];
        if (pre.indexOf(rank) > -1) {
            let prefix = prefixes.get(rank);
            name = name.replace(' ' + prefix + ' ',
                '</span> ' + prefix + ' <span class="namebit">');
        }
        formattedName += name;

        if (includeAuthor && taxon.name.scientificNameAuthorship) {
            formattedName += ` <span class="author">${taxon.name.scientificNameAuthorship}</span>`;
        }

        if (taxon.type == 'Cultivar') {
            formattedName.replace(/'(.+)'/g, '<span class="cv">&apos;$1&apos;</span>');
        }

        if (typeof taxon.taxonomicStatus !== 'undefined') {
            formattedName += '</span>';
        }

        return formattedName;
    }
    else {
        return '<i class="fa fa-spinner fa-spin"></i>';
    }
};

export { formatName };
