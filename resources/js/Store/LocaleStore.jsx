import { atom, selector } from "recoil";

const todoListState = atom({
  key: "todoListState",
  default: [
    {
      isComplete: true,
      title: "Published item",
    },
    {
      isComplete: false,
      title: "Unpublished item",
    },
  ],
});

export const todoListFilterState = atom({
  key: "todoListFilterState",
  default: "Show All",
});

export const filteredTodoListState = selector({
  key: "filteredTodoListState",
  get: ({ get }) => {
    const filter = get(todoListFilterState);
    const list = get(todoListState);

    switch (filter) {
      case "Show Completed":
        return list.filter((item) => item.isComplete);
      case "Show Uncompleted":
        return list.filter((item) => !item.isComplete);
      default:
        return list;
    }
  },
});
