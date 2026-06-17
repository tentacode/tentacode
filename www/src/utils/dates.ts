const MONTH_MAP: Record<string, string> = {
  'janv': 'janvier', 'jan': 'janvier',
  'févr': 'février', 'fév': 'février', 'fevr': 'février', 'fev': 'février',
  'mars': 'mars',
  'avr': 'avril',
  'mai': 'mai',
  'juin': 'juin',
  'juil': 'juillet',
  'août': 'août', 'aout': 'août',
  'sept': 'septembre', 'sep': 'septembre',
  'oct': 'octobre',
  'nov': 'novembre',
  'déc': 'décembre', 'dec': 'décembre',
};

export function expandFrenchMonths(str: string): string {
  return str.replace(
    /\b(janv|jan|févr|fév|fevr|fev|mars|avr|mai|juin|juil|août|aout|sept|sep|oct|nov|déc|dec)\.?\b/gi,
    (match) => {
      const key = match.replace(/\.$/, '').toLowerCase().normalize('NFC');
      const expanded = MONTH_MAP[key];
      if (!expanded) return match;
      return match[0] === match[0].toUpperCase()
        ? expanded.charAt(0).toUpperCase() + expanded.slice(1)
        : expanded;
    }
  );
}
