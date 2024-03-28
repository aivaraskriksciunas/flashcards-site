import { cva } from "class-variance-authority";

export { default as Button } from "./Button.vue";

export const buttonVariants = cva(
  "inline-flex items-center justify-center text-sm ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50",
  {
    variants: {
      variant: {
        default: "bg-primary text-primary-foreground hover:bg-primary/90 rounded-md font-medium",
        destructive:
          "bg-destructive text-destructive-foreground hover:bg-destructive/90 rounded-md font-medium",
        outline:
          "border border-border bg-transparent hover:bg-accent hover:text-accent-foreground rounded-md font-medium",
        secondary:
          "bg-secondary text-secondary-foreground hover:bg-secondary/80 rounded-md font-medium",
        ghost: "hover:bg-accent hover:text-accent-foreground rounded-md",
        link: "text-primary underline-offset-4 hover:underline rounded-md font-medium",
        pill: "bg-primary text-primary-foreground hover:bg-primary/90 rounded-full font-medium",
        pillOutline: "border border-primary hover:bg-primary/80 rounded-full font-medium"
      },
      size: {
        default: "px-4 py-2",
        sm: "h-9 px-3",
        lg: "h-11 px-8",
        icon: "h-10 w-10",
      },
    },
    defaultVariants: {
      variant: "default",
      size: "default",
    },
  }
);
