import { Machine } from "xstate";
import { assign } from "xstate/lib/actionTypes";

const localeMachine = Machine(
  {
    id: "locale",
    initial: "fi",
    states: {
      fi: {
        on: {
          SET_LOCALE: {
            target: "fi",
            actions: ["setLocale"],
          },
        },
      },
      en: {
        on: {
          SET_LOCALE: {
            target: "en",
            actions: ["setLocale"],
          },
        },
      },
      sv: {
        on: {
          SET_LOCALE: {
            target: "sv",
            actions: ["setLocale"],
          },
        },
      },
    },
  },
  {
    actions: {
      setLocale: (locale) => {
        console.log(`Set locale to ${locale}`);
      },
    },
  }
);

export default localeMachine;
